<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;
use Illuminate\Support\Facades\Schema;

class ContactQueryController extends Controller
{
    public function bulkAction(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:resolve,unresolve,delete'],
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'exists:contact_queries,id'],
        ]);

        $selectedIds = array_unique($data['selected']);
        $query = ContactQuery::query()->whereIn('id', $selectedIds);

        if ($data['action'] === 'resolve') {
            $query->update(['resolved_at' => now()]);
            $message = count($selectedIds) . ' contact queries marked as resolved.';
        } elseif ($data['action'] === 'unresolve') {
            $query->update(['resolved_at' => null]);
            $message = count($selectedIds) . ' contact queries marked as unresolved.';
        } else {
            $query->delete();
            $message = count($selectedIds) . ' contact queries deleted successfully.';
        }

        return redirect()->route('admin.contact-queries.index')
            ->with('status', $message);
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        if (!Schema::hasTable('contact_queries')) {
            $contactQueries = ContactQuery::query()->whereRaw('1 = 0')->paginate(15);
            return view('admin.contact-queries.index', compact('contactQueries', 'search', 'status'));
        }

        $contactQueries = ContactQuery::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->when($status === 'resolved', function ($query) {
                $query->whereNotNull('resolved_at');
            })
            ->when($status === 'unresolved', function ($query) {
                $query->whereNull('resolved_at');
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('admin.contact-queries.index', compact('contactQueries', 'search', 'status'));
    }

    public function show(ContactQuery $contactQuery)
    {
        return view('admin.contact-queries.show', compact('contactQuery'));
    }

    public function toggleResolved(ContactQuery $contactQuery)
    {
        $contactQuery->update([
            'resolved_at' => $contactQuery->resolved_at ? null : now(),
        ]);

        return redirect()->back()->with(
            'status',
            $contactQuery->resolved_at
                ? 'Contact query marked as resolved.'
                : 'Contact query marked as unresolved.'
        );
    }

    public function destroy(ContactQuery $contactQuery)
    {
        $contactQuery->delete();

        return redirect()->route('admin.contact-queries.index')
            ->with('status', 'Contact query deleted successfully.');
    }
}
