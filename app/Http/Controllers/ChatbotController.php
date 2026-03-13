<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
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

    private const SYNONYMS = [
        'money back' => 'refund',
        'send back' => 'return',
        'cancel purchase' => 'cancel order',
        'cancel my order' => 'cancel order',
        'track package' => 'track order',
        'where is my order' => 'order status',
        'log in' => 'login',
        'sign in' => 'login',
        'sign up' => 'register',
        'gaming pc' => 'pc',
        'in stock' => 'available',
        'out of stock' => 'unavailable',
        'available now' => 'available',
        'open hours' => 'opening hours',
        'when are you open' => 'opening hours',
        'opening times' => 'opening hours',
        'recommend me' => 'recommend',
        'what should i buy' => 'recommend',
        'best product' => 'recommend',
        'delete my account' => 'account deletion',
        'remove my account' => 'account deletion',
        'close my account' => 'account deletion',
        'card payment' => 'payment',
        'pay by card' => 'payment',
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
            'keywords' => ['return', 'returns', 'refund', 'exchange', 'cancel order', 'replacement'],
            'faq_keywords' => ['returns', 'refund'],
            'suggestions' => [
                ['label' => 'Start a return', 'message' => 'How do I start a return?'],
                ['label' => 'Order help', 'message' => 'How do I check my order status?'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
            ],
        ],
        'orders' => [
            'keywords' => ['order', 'orders', 'track', 'tracking', 'status', 'purchase', 'bought'],
            'faq_keywords' => ['order', 'orders', 'status'],
            'suggestions' => [
                ['label' => 'Track my order', 'message' => 'How do I track my order?'],
                ['label' => 'Previous orders', 'url' => '/orders'],
                ['label' => 'Need returns help', 'message' => 'How do returns work?'],
            ],
        ],
        'account' => [
            'keywords' => ['account', 'login', 'register', 'profile', 'password', 'reset', 'email'],
            'faq_keywords' => ['password', 'account'],
            'suggestions' => [
                ['label' => 'Profile info', 'url' => '/profile'],
                ['label' => 'Login page', 'url' => '/pages/login.html'],
                ['label' => 'Reset password', 'message' => 'How do I reset my password?'],
            ],
        ],
        'payment' => [
            'keywords' => ['payment', 'card', 'visa', 'mastercard', 'paypal', 'checkout', 'billing'],
            'faq_keywords' => ['payment', 'checkout'],
            'suggestions' => [
                ['label' => 'Checkout help', 'message' => 'I need help with checkout.'],
                ['label' => 'Order help', 'message' => 'How do I check my order status?'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
            ],
        ],
        'stock' => [
            'keywords' => ['stock', 'available', 'availability', 'unavailable', 'sold out', 'restock'],
            'faq_keywords' => ['stock', 'available'],
            'suggestions' => [
                ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'],
                ['label' => 'Consoles and PCs', 'url' => '/pages/ShopAll.html?category=Consoles%20and%20PCs'],
                ['label' => 'Video games', 'url' => '/pages/ShopAll.html?category=Games'],
            ],
        ],
        'recommendations' => [
            'keywords' => ['recommend', 'suggest', 'best', 'looking for', 'which product', 'what should i buy'],
            'faq_keywords' => ['recommend', 'product'],
            'suggestions' => [
                ['label' => 'Video games', 'url' => '/pages/ShopAll.html?category=Games'],
                ['label' => 'Consoles and PCs', 'url' => '/pages/ShopAll.html?category=Consoles%20and%20PCs'],
                ['label' => 'Accessories', 'url' => '/pages/ShopAll.html?category=Accessories'],
            ],
        ],
        'account_deletion' => [
            'keywords' => ['account deletion', 'delete account', 'remove account', 'close account'],
            'faq_keywords' => ['account', 'delete'],
            'suggestions' => [
                ['label' => 'Profile info', 'url' => '/profile'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
                ['label' => 'Account help', 'message' => 'I need help with my account.'],
            ],
        ],
        'opening_hours' => [
            'keywords' => ['opening hours', 'open', 'hours', 'closing time', 'open today'],
            'faq_keywords' => ['hours', 'open'],
            'suggestions' => [
                ['label' => 'Contact us', 'url' => '/pages/index.html#contactus'],
                ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'],
                ['label' => 'Store help', 'message' => 'How can I contact support?'],
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
            'message' => 'required|string|max:500',
        ]);

        $message = trim($request->input('message'));
        $faqs = Schema::hasTable('faqs') ? Faq::query()->get() : collect();
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
        $bestFaq = $this->findBestFaq($message, $intent, $faqs, $context);
        $reply = $bestFaq['faq']?->answer;
        $fallbackSuggestions = [];

        if (! $reply) {
            [$reply, $fallbackSuggestions] = $this->buildFallback($message, $intent, $context);
        }

        if ($reply && $intent) {
            $reply = $this->appendContextualHelp($reply, $intent);
        }

        $suggestions = $fallbackSuggestions ?: $this->suggestionsForIntent($intent);

        $request->session()->put('chatbot_context', [
            'intent' => $intent,
            'message' => $message,
            'normalized_message' => $this->normalize($message),
            'tokens' => $this->tokenize($message),
            'faq_keyword' => $bestFaq['faq']?->keyword,
            'faq_category' => $bestFaq['faq']?->category,
            'recent_intents' => $this->rememberRecentIntent($context, $intent),
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
        $recentIntents = $context['recent_intents'] ?? [];
        $isFollowUp = $this->looksLikeFollowUp($normalized);
        $isShortMessage = count($tokens) <= 4;

        foreach (self::INTENTS as $intent => $config) {
            $score = 0;

            foreach ($config['keywords'] as $keyword) {
                $keywordNormalized = $this->normalize($keyword);
                $keywordTokens = $this->tokenize($keyword);

                if ($keywordNormalized !== '' && str_contains($normalized, $keywordNormalized)) {
                    $score += 9;
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

            if (($context['intent'] ?? null) === $intent && ($isFollowUp || $isShortMessage)) {
                $score += 8;
            }

            if (in_array($intent, $recentIntents, true)) {
                $score += 2;
            }

            $scores[$intent] = $score;
        }

        arsort($scores);
        $bestIntent = array_key_first($scores);
        $bestScore = $bestIntent ? $scores[$bestIntent] : 0;

        if ($bestScore >= 5) {
            return $bestIntent;
        }

        if (($isFollowUp || $isShortMessage) && ! empty($context['intent'])) {
            return $context['intent'];
        }

        return null;
    }

    private function findBestFaq(string $message, ?string $intent, $faqs, array $context): array
    {
        $tokens = $this->tokenize($message);
        $normalized = $this->normalize($message);
        $bestFaq = null;
        $bestScore = 0;
        $preferredFaqKeywords = $intent ? (self::INTENTS[$intent]['faq_keywords'] ?? []) : [];
        $previousFaqKeyword = $this->normalize((string) ($context['faq_keyword'] ?? ''));
        $previousFaqCategory = $context['faq_category'] ?? null;

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
                if (str_contains($faqKeyword, $this->normalize($preferredKeyword))) {
                    $score += 12;
                }
            }

            if ($intent && ($faq->category ?? 'general') === $intent) {
                $score += 18;
            }

            if (($faq->category ?? 'general') === 'general') {
                $score += 1;
            }

            if ($previousFaqKeyword !== '' && $faqKeyword === $previousFaqKeyword && $this->looksLikeFollowUp($normalized)) {
                $score += 10;
            }

            if ($previousFaqCategory && ($faq->category ?? null) === $previousFaqCategory && $this->looksLikeFollowUp($normalized)) {
                $score += 8;
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

    private function buildFallback(string $message, ?string $intent, array $context): array
    {
        $tokens = array_values(array_unique(array_merge(
            $this->tokenize($message),
            array_slice($context['tokens'] ?? [], 0, 3)
        )));

        $categoryFilters = $this->guessRelevantProductCategories($tokens, $intent, $context);

        $products = Product::query()
            ->with('category')
            ->when(! empty($categoryFilters), function ($query) use ($categoryFilters) {
                $query->whereHas('category', function ($categoryQuery) use ($categoryFilters) {
                    $categoryQuery->where(function ($inner) use ($categoryFilters) {
                        foreach ($categoryFilters as $filter) {
                            $inner->orWhere('name', 'like', '%' . $filter . '%');
                        }
                    });
                });
            })
            ->when(! empty($tokens), function ($query) use ($tokens) {
                $query->where(function ($inner) use ($tokens) {
                    foreach (array_slice($tokens, 0, 4) as $token) {
                        $inner->orWhere('name', 'like', '%' . $token . '%')
                            ->orWhere('platform', 'like', '%' . $token . '%');
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

        foreach ($this->buildCategorySuggestions($categoryFilters) as $categorySuggestion) {
            $suggestions[] = $categorySuggestion;
        }

        $suggestions = collect($suggestions)
            ->unique(fn ($item) => ($item['label'] ?? '') . '|' . ($item['url'] ?? '') . '|' . ($item['message'] ?? ''))
            ->take(4)
            ->values()
            ->all();

        $reply = match ($intent) {
            'orders' => "I think you're asking about an order, but I couldn't find the exact FAQ answer. These next steps should still help.",
            'shipping' => "This sounds like a shipping question. I couldn't find the exact FAQ match, but these options should help.",
            'returns' => "This looks like a returns question. I couldn't find the exact FAQ match, but these links should get you there.",
            'payment' => "This seems payment-related. I couldn't find the exact FAQ answer, but these options should help you continue.",
            'stock' => "This sounds like an availability question. I couldn't find the exact FAQ answer, but you can keep going from here.",
            'recommendations' => "It sounds like you want product suggestions. I couldn't match a specific FAQ, but these browsing links should help.",
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
            'account', 'account_deletion' => $reply . ' Your profile page is available from the account menu if you want to manage account details.',
            default => $reply,
        };
    }

    private function rememberRecentIntent(array $context, ?string $intent): array
    {
        $recent = collect($context['recent_intents'] ?? []);

        if ($intent) {
            $recent->prepend($intent);
        }

        return $recent
            ->filter()
            ->unique()
            ->take(3)
            ->values()
            ->all();
    }

    private function guessRelevantProductCategories(array $tokens, ?string $intent, array $context): array
    {
        $filters = [];
        $joined = implode(' ', $tokens);
        $previousMessage = (string) ($context['normalized_message'] ?? '');

        if (in_array($intent, ['recommendations', 'stock'], true)) {
            $filters[] = 'Games';
            $filters[] = 'Accessories';
            $filters[] = 'Consoles';
            $filters[] = 'PC';
        }

        if (str_contains($joined, 'game') || str_contains($joined, 'ps5') || str_contains($joined, 'xbox') || str_contains($joined, 'nintendo')) {
            $filters[] = 'Games';
        }

        if (str_contains($joined, 'console') || str_contains($joined, 'pc') || str_contains($joined, 'playstation') || str_contains($joined, 'xbox')) {
            $filters[] = 'Consoles';
            $filters[] = 'PC';
        }

        if (str_contains($joined, 'chair') || str_contains($joined, 'desk') || str_contains($joined, 'monitor') || str_contains($joined, 'display')) {
            $filters[] = 'Hardware';
        }

        if (str_contains($joined, 'controller') || str_contains($joined, 'headset') || str_contains($joined, 'accessory')) {
            $filters[] = 'Accessories';
        }

        if ($previousMessage !== '' && $this->looksLikeFollowUp($this->normalize($joined))) {
            if (str_contains($previousMessage, 'game')) {
                $filters[] = 'Games';
            }
            if (str_contains($previousMessage, 'console') || str_contains($previousMessage, 'pc')) {
                $filters[] = 'Consoles';
                $filters[] = 'PC';
            }
        }

        return array_values(array_unique($filters));
    }

    private function buildCategorySuggestions(array $categoryFilters): array
    {
        $categories = Category::query()
            ->when(! empty($categoryFilters), function ($query) use ($categoryFilters) {
                $query->where(function ($inner) use ($categoryFilters) {
                    foreach ($categoryFilters as $filter) {
                        $inner->orWhere('name', 'like', '%' . $filter . '%');
                    }
                });
            })
            ->orderBy('name')
            ->limit(2)
            ->get(['name']);

        return $categories->map(function ($category) {
            return [
                'label' => Str::limit($category->name, 24),
                'url' => '/pages/ShopAll.html?category=' . urlencode($category->name),
            ];
        })->all();
    }

    private function looksLikeFollowUp(string $normalized): bool
    {
        foreach ([
            'that', 'this', 'it', 'they', 'them', 'those', 'more', 'what about', 'how long',
            'and', 'also', 'what else', 'another one', 'can you explain', 'tell me more',
        ] as $term) {
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

        foreach (self::SYNONYMS as $from => $to) {
            $text = str_replace($from, $to, $text);
        }

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
