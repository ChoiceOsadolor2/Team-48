<?php

namespace App\Http\Controllers;

use App\Models\ContactQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactQueryController extends Controller
{
    public function store(Request $request)
    {
        if (!Schema::hasTable('contact_queries')) {
            return response()->json([
                'success' => false,
                'message' => 'Contact messages are unavailable right now.',
            ], 503);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactQuery::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully.',
        ]);
    }
}
