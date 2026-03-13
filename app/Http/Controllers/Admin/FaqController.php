<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FaqController extends Controller
{
    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:delete'],
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'exists:faqs,id'],
        ]);

        $selectedIds = array_unique($data['selected']);

        Faq::query()->whereIn('id', $selectedIds)->delete();

        return redirect()->route('admin.faqs.index')
            ->with('status', count($selectedIds) . ' FAQs deleted successfully.');
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));

        $faqs = Schema::hasTable('faqs')
            ? Faq::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('keyword', 'like', "%{$search}%")
                            ->orWhere('answer', 'like', "%{$search}%");
                    });
                })
                ->when($category !== '', fn ($query) => $query->where('category', $category))
                ->orderBy('category')
                ->orderBy('keyword')
                ->paginate(15)
                ->appends($request->query())
            : collect();

        $categories = Faq::CATEGORIES;

        return view('admin.faqs.index', compact('faqs', 'search', 'category', 'categories'));
    }

    public function create()
    {
        $categories = Faq::CATEGORIES;

        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'keyword' => ['required', 'string', 'max:255', 'unique:faqs,keyword'],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys(Faq::CATEGORIES))],
            'answer' => ['required', 'string'],
        ]);

        Faq::create($data);

        return redirect()->route('admin.faqs.index')
            ->with('status', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $categories = Faq::CATEGORIES;

        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'keyword' => ['required', 'string', 'max:255', 'unique:faqs,keyword,' . $faq->id],
            'category' => ['required', 'string', 'in:' . implode(',', array_keys(Faq::CATEGORIES))],
            'answer' => ['required', 'string'],
        ]);

        $faq->update($data);

        return redirect()->route('admin.faqs.index')
            ->with('status', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('status', 'FAQ deleted successfully.');
    }
}
