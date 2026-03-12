<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::query()->orderBy('keyword')->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'keyword' => ['required', 'string', 'max:255', 'unique:faqs,keyword'],
            'answer' => ['required', 'string'],
        ]);

        Faq::create($data);

        return redirect()->route('admin.faqs.index')
            ->with('status', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'keyword' => ['required', 'string', 'max:255', 'unique:faqs,keyword,' . $faq->id],
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
