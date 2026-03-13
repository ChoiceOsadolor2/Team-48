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

    public function destroy(ContactQuery $contactQuery)
    {
        $contactQuery->delete();

        return redirect()->route('admin.contact-queries.index')
            ->with('status', 'Contact query deleted successfully.');
    }
}
