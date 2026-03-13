<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;

class ContactQueryController extends Controller
{
    public function index()
    {
        $contactQueries = ContactQuery::query()
            ->latest()
            ->get();

        return view('admin.contact-queries.index', compact('contactQueries'));
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
