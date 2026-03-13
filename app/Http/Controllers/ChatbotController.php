<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    private const STOP_WORDS = [
        'a', 'an', 'and', 'are', 'be', 'can', 'do', 'for', 'from', 'get', 'give',
        'hello', 'help', 'hey', 'how', 'i', 'im', 'is', 'it', 'me', 'my', 'of',
        'on', 'or', 'please', 'tell', 'that', 'the', 'this', 'to', 'us', 'we',
        'what', 'with', 'you', 'your',
    ];

    private const INTENTS = [
        'shipping' => [
            'keywords' => ['shipping', 'delivery', 'deliver', 'dispatch', 'arrive', 'arrival', 'postage', 'ship', 'shipped'],
            'faq_keywords' => ['shipping', 'delivery'],
            'suggestions' => [
                ['label' => 'Shipping times', 'message' => 'How long does shipping take?'],
                ['label' => 'Delivery options', 'message' => 'What delivery options do you offer?'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
            ],
        ],
        'returns' => [
            'keywords' => ['return', 'returns', 'refund', 'exchange', 'send back', 'cancel order', 'replacement'],
            'faq_keywords' => ['returns'],
            'suggestions' => [
                ['label' => 'Start a return', 'message' => 'How do I start a return?'],
                ['label' => 'Order help', 'message' => 'How do I check my order status?'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
            ],
        ],
        'orders' => [
            'keywords' => ['order', 'orders', 'track', 'tracking', 'status', 'where', 'purchase', 'bought'],
            'faq_keywords' => ['order', 'orders', 'status'],
            'suggestions' => [
                ['label' => 'Track my order', 'message' => 'How do I track my order?'],
                ['label' => 'Previous orders', 'url' => '/orders'],
                ['label' => 'Need returns help', 'message' => 'How do returns work?'],
            ],
        ],
        'account' => [
            'keywords' => ['account', 'login', 'sign in', 'register', 'profile', 'password', 'reset', 'email'],
            'faq_keywords' => ['password'],
            'suggestions' => [
                ['label' => 'Profile info', 'url' => '/profile'],
                ['label' => 'Login page', 'url' => '/pages/login.html'],
                ['label' => 'Reset password', 'message' => 'How do I reset my password?'],
            ],
        ],
        'contact' => [
            'keywords' => ['contact', 'support', 'agent', 'human', 'phone', 'email', 'speak', 'talk'],
            'faq_keywords' => ['contact'],
            'suggestions' => [
                ['label' => 'Contact us', 'url' => '/pages/index.html#contactus'],
                ['label' => 'Shipping help', 'message' => 'I need help with shipping.'],
                ['label' => 'Returns help', 'message' => 'I need help with returns.'],
            ],
        ],
        'greeting' => [
            'keywords' => ['hello', 'hey', 'hi', 'yo'],
            'faq_keywords' => ['hello', 'hi', 'help'],
            'suggestions' => [
                ['label' => 'Shipping help', 'message' => 'How long does shipping take?'],
                ['label' => 'Track order', 'message' => 'How do I track my order?'],
                ['label' => 'Returns', 'message' => 'How do returns work?'],
            ],
        ],
    ];

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $message = trim($request->input('message'));
        $faqs = Schema::hasTable('faqs')
            ? Faq::query()->get()
            : collect();
        $context = $request->session()->get('chatbot_context', []);

        if ($faqs->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'reply' => "I can't find the local FAQ knowledge base yet. Please run the site setup so I can answer store questions.",
                'suggestions' => [
                    ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
                    ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'],
                ],
            ]);
        }

        $intent = $this->detectIntent($message, $context);
        $bestFaq = $this->findBestFaq($message, $intent, $faqs);
        $reply = $bestFaq['faq']?->answer;
        $fallbackSuggestions = [];

        if (! $reply) {
            [$reply, $fallbackSuggestions] = $this->buildFallback($message, $intent);
        }

        if ($reply && $intent) {
            $reply = $this->appendContextualHelp($reply, $intent);
        }

        $suggestions = $fallbackSuggestions ?: $this->suggestionsForIntent($intent);

        $request->session()->put('chatbot_context', [
            'intent' => $intent,
            'message' => $message,
            'updated_at' => now()->toIso8601String(),
        ]);

        return response()->json([
            'status' => 'success',
            'reply' => $reply ?: "I'm not entirely sure about that yet, but I can still point you to the right page.",
            'intent' => $intent,
            'suggestions' => $suggestions,
        ]);
    }

    private function detectIntent(string $message, array $context): ?string
    {
        $normalized = $this->normalize($message);
        $tokens = $this->tokenize($message);
        $scores = [];

        foreach (self::INTENTS as $intent => $config) {
            $score = 0;

            foreach ($config['keywords'] as $keyword) {
                $keywordNormalized = $this->normalize($keyword);
                $keywordTokens = $this->tokenize($keyword);

                if ($keywordNormalized !== '' && str_contains($normalized, $keywordNormalized)) {
                    $score += 8;
                }

                foreach ($tokens as $token) {
                    foreach ($keywordTokens as $keywordToken) {
                        if ($token === $keywordToken) {
                            $score += 5;
                        } elseif ($this->isCloseMatch($token, $keywordToken)) {
                            $score += 3;
                        }
                    }
                }
            }

            $scores[$intent] = $score;
        }

        arsort($scores);
        $bestIntent = array_key_first($scores);
        $bestScore = $bestIntent ? $scores[$bestIntent] : 0;

        if ($bestScore >= 5) {
            return $bestIntent;
        }

        if ($this->looksLikeFollowUp($normalized) && ! empty($context['intent'])) {
            return $context['intent'];
        }

        return null;
    }

    private function findBestFaq(string $message, ?string $intent, $faqs): array
    {
        $tokens = $this->tokenize($message);
        $normalized = $this->normalize($message);
        $bestFaq = null;
        $bestScore = 0;
        $preferredFaqKeywords = $intent ? (self::INTENTS[$intent]['faq_keywords'] ?? []) : [];

        foreach ($faqs as $faq) {
            $faqKeyword = $this->normalize($faq->keyword);
            $faqTokens = $this->tokenize($faq->keyword);
            $score = 0;

            if ($faqKeyword !== '' && str_contains($normalized, $faqKeyword)) {
                $score += 20;
            }

            foreach ($tokens as $token) {
                foreach ($faqTokens as $faqToken) {
                    if ($token === $faqToken) {
                        $score += 8;
                    } elseif ($this->isCloseMatch($token, $faqToken)) {
                        $score += 5;
                    }
                }
            }

            foreach ($preferredFaqKeywords as $preferredKeyword) {
                if ($faqKeyword === $this->normalize($preferredKeyword)) {
                    $score += 12;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestFaq = $faq;
            }
        }

        return [
            'faq' => $bestScore >= 8 ? $bestFaq : null,
            'score' => $bestScore,
        ];
    }

    private function buildFallback(string $message, ?string $intent): array
    {
        $tokens = $this->tokenize($message);

        $products = Product::query()
            ->when(! empty($tokens), function ($query) use ($tokens) {
                $query->where(function ($inner) use ($tokens) {
                    foreach (array_slice($tokens, 0, 4) as $token) {
                        $inner->orWhere('name', 'like', '%' . $token . '%');
                    }
                });
            })
            ->limit(3)
            ->get();

        $suggestions = $this->suggestionsForIntent($intent);

        foreach ($products as $product) {
            $suggestions[] = [
                'label' => Str::limit($product->name, 28),
                'url' => '/pages/ProductPage.html?id=' . $product->id,
            ];
        }

        $suggestions[] = ['label' => 'Browse all products', 'url' => '/pages/ShopAll.html'];
        $suggestions[] = ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'];

        $suggestions = collect($suggestions)
            ->unique(fn ($item) => ($item['label'] ?? '') . '|' . ($item['url'] ?? '') . '|' . ($item['message'] ?? ''))
            ->take(4)
            ->values()
            ->all();

        $reply = match ($intent) {
            'orders' => "I couldn't find an exact FAQ answer, but I can still point you toward order-related pages that may help.",
            'shipping' => "I couldn't find an exact shipping FAQ match, but here are the best places to continue.",
            'returns' => "I couldn't find a precise returns answer, but these links should get you to the right place.",
            default => "I couldn't find an exact FAQ match, but these options should help you keep going.",
        };

        return [$reply, $suggestions];
    }

    private function suggestionsForIntent(?string $intent): array
    {
        if ($intent && isset(self::INTENTS[$intent]['suggestions'])) {
            return self::INTENTS[$intent]['suggestions'];
        }

        return [
            ['label' => 'Shipping help', 'message' => 'How long does shipping take?'],
            ['label' => 'Track an order', 'message' => 'How do I track my order?'],
            ['label' => 'Returns', 'message' => 'How do returns work?'],
        ];
    }

    private function appendContextualHelp(string $reply, ?string $intent): string
    {
        if (! Auth::check()) {
            return $reply;
        }

        return match ($intent) {
            'orders' => $reply . ' You can also open your Previous Orders page from the account menu.',
            'account' => $reply . ' Your profile page is available from the account menu if you want to update details.',
            default => $reply,
        };
    }

    private function looksLikeFollowUp(string $normalized): bool
    {
        foreach (['that', 'this', 'it', 'they', 'them', 'those', 'more', 'what about', 'how long', 'and', 'also'] as $term) {
            if (str_contains($normalized, $term)) {
                return true;
            }
        }

        return false;
    }

    private function tokenize(string $text): array
    {
        $normalized = $this->normalize($text);
        $parts = preg_split('/\s+/', $normalized) ?: [];

        return array_values(array_filter($parts, function ($token) {
            return $token !== '' && ! in_array($token, self::STOP_WORDS, true) && strlen($token) >= 2;
        }));
    }

    private function normalize(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        return trim($text);
    }

    private function isCloseMatch(string $input, string $candidate): bool
    {
        if ($input === $candidate) {
            return true;
        }

        if (strlen($input) < 4 || strlen($candidate) < 4) {
            return false;
        }

        return levenshtein($input, $candidate) <= 1;
    }
}
