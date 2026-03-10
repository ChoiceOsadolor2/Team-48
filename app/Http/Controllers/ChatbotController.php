<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Faq;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $userMessage = strtolower($request->input('message'));
        $words = explode(' ', $userMessage);

        foreach ($words as $word) {
            if (strlen($word) < 3) continue; // Skip very short words like 'is', 'to', etc.

            $faq = Faq::where('keyword', 'LIKE', '%' . $word . '%')->first();

            if ($faq) {
                return response()->json([
                    'status' => 'success',
                    'reply' => $faq->answer
                ]);
            }
        }

        // Default Fallback
        return response()->json([
            'status' => 'success',
            'reply' => "I'm not entirely sure about that! For specific help, please contact our support team via the Contact Us page."
        ]);
    }
}
