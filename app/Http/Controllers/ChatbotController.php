<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Category;
use App\Models\Product;
use App\Support\InputSanitizer;
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
        'cncel order' => 'cancel order',
        'track package' => 'track order',
        'where is my order' => 'order status',
        'wheres my order' => 'order status',
        'where my order' => 'order status',
        'track my order' => 'order status',
        'ordr status' => 'order status',
        'ordr' => 'order',
        'log in' => 'login',
        'sign in' => 'login',
        'signin' => 'login',
        'sign up' => 'register',
        'signup' => 'register',
        'gaming pc' => 'pc',
        'in stock' => 'available',
        'out of stock' => 'unavailable',
        'available now' => 'available',
        'instock' => 'available',
        'restok' => 'restock',
        'open hours' => 'opening hours',
        'when are you open' => 'opening hours',
        'opening times' => 'opening hours',
        'recommend me' => 'recommend',
        'what should i buy' => 'recommend',
        'what should i get' => 'recommend',
        'best product' => 'recommend',
        'delete my account' => 'account deletion',
        'remove my account' => 'account deletion',
        'close my account' => 'account deletion',
        'card payment' => 'payment',
        'pay by card' => 'payment',
        'paymnt' => 'payment',
        'refun' => 'refund',
        'retun' => 'return',
        'contoller' => 'controller',
        'cntact' => 'contact',
        'suport' => 'support',
        'shippng' => 'shipping',
        'delivry' => 'delivery',
        'availble' => 'available',
        'ps five' => 'ps5',
        'x box' => 'xbox',
    ];

    private const INTENTS = [
        'shipping' => [
            'keywords' => ['shipping', 'delivery', 'deliver', 'dispatch', 'arrive', 'arrival', 'postage', 'ship', 'shipped'],
            'faq_keywords' => ['shipping', 'delivery'],
            'suggestions' => [
                ['label' => 'Order history', 'url' => '/orders'],
                ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
            ],
        ],
        'returns' => [
            'keywords' => ['return', 'returns', 'refund', 'exchange', 'cancel order', 'replacement'],
            'faq_keywords' => ['returns', 'refund'],
            'suggestions' => [
                ['label' => 'Start a refund', 'url' => '/orders'],
                ['label' => 'Order history', 'url' => '/orders'],
                ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'],
            ],
        ],
        'orders' => [
            'keywords' => ['order', 'orders', 'track', 'tracking', 'status', 'purchase', 'bought'],
            'faq_keywords' => ['order', 'orders', 'status'],
            'suggestions' => [
                ['label' => 'Track my order', 'url' => '/orders'],
                ['label' => 'Previous orders', 'url' => '/orders'],
                ['label' => 'Returns & refunds', 'url' => '/orders'],
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
                ['label' => 'Go to checkout', 'url' => '/checkout'],
                ['label' => 'Order history', 'url' => '/orders'],
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
                ['label' => 'Login page', 'url' => '/pages/login.html'],
            ],
        ],
        'opening_hours' => [
            'keywords' => ['opening hours', 'open', 'hours', 'closing time', 'open today'],
            'faq_keywords' => ['hours', 'open'],
            'suggestions' => [
                ['label' => 'Contact us', 'url' => '/pages/index.html#contactus'],
                ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'],
                ['label' => 'Order history', 'url' => '/orders'],
            ],
        ],
        'contact' => [
            'keywords' => ['contact', 'support', 'agent', 'human', 'phone', 'email', 'speak', 'talk'],
            'faq_keywords' => ['contact'],
            'suggestions' => [
                ['label' => 'Contact us', 'url' => '/pages/index.html#contactus'],
                ['label' => 'Order history', 'url' => '/orders'],
                ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'],
            ],
        ],
        'greeting' => [
            'keywords' => ['hello', 'hey', 'hi', 'yo'],
            'faq_keywords' => ['hello', 'hi', 'help'],
            'suggestions' => [
                ['label' => 'Shop products', 'url' => '/pages/ShopAll.html'],
                ['label' => 'Track order', 'url' => '/orders'],
                ['label' => 'Contact us', 'url' => '/pages/index.html#contactus'],
            ],
        ],
    ];

    public function ask(Request $request)
    {
        $request->merge([
            'message' => InputSanitizer::singleLine($request->input('message')),
        ]);

        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = $request->input('message');
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

        $intents = $this->detectIntents($message, $context);
        $intent = $intents[0] ?? null;
        $bestFaq = $this->findBestFaq($message, $intents, $faqs, $context);
        $reply = $bestFaq['faq']?->answer;
        $fallbackSuggestions = [];

        if (! $reply) {
            [$reply, $fallbackSuggestions] = $this->buildFallback($message, $intents, $context);
        }

        if ($this->isGenericOrderSupportRequest($message, $intents)) {
            $reply = "I can help with order-related queries. Please use the options below to track your order, open your order history, or access returns and refunds support.";
            $fallbackSuggestions = [];
        }

        if ($reply && ! empty($intents)) {
            $reply = $this->appendContextualHelp($reply, $intents);
        }

        $suggestions = $fallbackSuggestions ?: $this->suggestionsForIntents($intents, $context);

        if ($intent === 'greeting') {
            $reply = "Hi, how can I help you today?";
            $suggestions = [];
        }

        $request->session()->put('chatbot_context', [
            'intent' => $intent,
            'intents' => $intents,
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
            'intents' => $intents,
            'suggestions' => $suggestions,
        ]);
    }

    private function detectIntents(string $message, array $context): array
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
        $selected = [];
        foreach ($scores as $intent => $score) {
            if ($score >= 5) {
                $selected[] = $intent;
            }

            if (count($selected) >= 2) {
                break;
            }
        }

        if (! empty($selected)) {
            return $selected;
        }

        if (($isFollowUp || $isShortMessage) && ! empty($context['intent'])) {
            return [$context['intent']];
        }

        return [];
    }

    private function findBestFaq(string $message, array $intents, $faqs, array $context): array
    {
        $tokens = $this->tokenize($message);
        $normalized = $this->normalize($message);
        $bestFaq = null;
        $bestScore = 0;
        $preferredFaqKeywords = collect($intents)
            ->flatMap(fn ($intent) => self::INTENTS[$intent]['faq_keywords'] ?? [])
            ->values()
            ->all();
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

            if (! empty($intents) && in_array(($faq->category ?? 'general'), $intents, true)) {
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

    private function buildFallback(string $message, array $intents, array $context): array
    {
        $tokens = array_values(array_unique(array_merge(
            $this->tokenize($message),
            array_slice($context['tokens'] ?? [], 0, 3)
        )));

        $primaryIntent = $intents[0] ?? null;
        $secondaryIntent = $intents[1] ?? null;
        $categoryFilters = $this->guessRelevantProductCategories($tokens, $primaryIntent, $context);

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

        $suggestions = $this->suggestionsForIntents($intents, $context);

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

        $reply = match ($primaryIntent) {
            'orders' => "I can help with order-related queries. Please review the options below to track your order, open your order history, or continue with returns and refunds support.",
            'shipping' => "This sounds like a shipping question. I couldn't find the exact FAQ match, but these options should help.",
            'returns' => "This looks like a returns question. I couldn't find the exact FAQ match, but these links should get you there.",
            'payment' => "This seems payment-related. I couldn't find the exact FAQ answer, but these options should help you continue.",
            'stock' => "This sounds like an availability question. I couldn't find the exact FAQ answer, but you can keep going from here.",
            'recommendations' => "It sounds like you want product suggestions. I couldn't match a specific FAQ, but these browsing links should help.",
            default => "I couldn't find an exact FAQ match, but these options should help you keep going.",
        };

        if ($primaryIntent && $secondaryIntent) {
            $reply .= ' I also picked up a second topic in your message, so I included actions for both.';
        }

        return [$reply, $suggestions];
    }

    private function suggestionsForIntents(array $intents, array $context = []): array
    {
        $suggestions = collect();

        foreach (array_slice($intents, 0, 2) as $intent) {
            foreach (self::INTENTS[$intent]['suggestions'] ?? [] as $suggestion) {
                $suggestions->push($suggestion);
            }
        }

        foreach ($this->contextualActionSuggestions($intents, $context) as $suggestion) {
            $suggestions->push($suggestion);
        }

        if ($suggestions->isEmpty()) {
            $suggestions = collect([
            ['label' => 'Shipping help', 'message' => 'How long does shipping take?'],
            ['label' => 'Track an order', 'message' => 'How do I track my order?'],
            ['label' => 'Returns', 'message' => 'How do returns work?'],
            ]);
        }

        return $suggestions
            ->unique(fn ($item) => ($item['label'] ?? '') . '|' . ($item['url'] ?? '') . '|' . ($item['message'] ?? ''))
            ->take(4)
            ->values()
            ->all();
    }

    private function appendContextualHelp(string $reply, array $intents): string
    {
        if (! Auth::check()) {
            return $reply;
        }

        foreach ($intents as $intent) {
            $reply = match ($intent) {
                'orders' => $this->appendIfMissing(
                    $reply,
                    ' You can view and manage your orders from the Previous Orders page in your account menu.'
                ),
                'account', 'account_deletion' => $reply . ' Your profile page is available from the account menu if you want to manage account details.',
                'returns' => $this->appendIfMissing(
                    $reply,
                    ' If your order is completed, you can start a return or refund from Order History.'
                ),
                default => $reply,
            };
        }

        return $reply;
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
            if (str_contains($joined, 'chair') || str_contains($joined, 'desk')) {
                $filters[] = 'Furniture';
            }

            if (str_contains($joined, 'monitor') || str_contains($joined, 'display')) {
                $filters[] = 'Hardware';
            }
        }

        if (str_contains($joined, 'controller') || str_contains($joined, 'headset') || str_contains($joined, 'accessory')) {
            $filters[] = 'Accessories';
        }

        if (str_contains($joined, 'furniture')) {
            $filters[] = 'Furniture';
        }

        if (str_contains($joined, 'merch') || str_contains($joined, 'hoodie') || str_contains($joined, 'shirt') || str_contains($joined, 'poster')) {
            $filters[] = 'Merchandise';
        }

        if (str_contains($joined, 'card') || str_contains($joined, 'cards') || str_contains($joined, 'trading card')) {
            $filters[] = 'Trading Cards';
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
        $text = trim($text);

        return $this->correctTypos($text);
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

    private function correctTypos(string $text): string
    {
        $parts = preg_split('/\s+/', $text) ?: [];
        $vocabulary = collect(self::INTENTS)
            ->flatMap(fn ($intent) => $intent['keywords'] ?? [])
            ->flatMap(fn ($phrase) => preg_split('/\s+/', $this->normalizeKnownPhrase($phrase)) ?: [])
            ->merge(collect(self::SYNONYMS)
                ->flatMap(fn ($replacement, $phrase) => array_merge(
                    preg_split('/\s+/', $this->normalizeKnownPhrase((string) $phrase)) ?: [],
                    preg_split('/\s+/', $this->normalizeKnownPhrase((string) $replacement)) ?: []
                )))
            ->merge(['refund', 'return', 'order', 'orders', 'shipping', 'delivery', 'payment', 'stock', 'available', 'support', 'contact', 'recommend', 'controller', 'headset', 'cards', 'login', 'register'])
            ->filter()
            ->unique()
            ->values()
            ->all();

        $corrected = array_map(function ($part) use ($vocabulary) {
            if (strlen($part) < 4) {
                return $part;
            }

            foreach ($vocabulary as $candidate) {
                if ($part === $candidate) {
                    return $part;
                }

                if (levenshtein($part, $candidate) === 1) {
                    return $candidate;
                }
            }

            return $part;
        }, $parts);

        return implode(' ', $corrected);
    }

    private function normalizeKnownPhrase(string $text): string
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        return trim($text);
    }

    private function contextualActionSuggestions(array $intents, array $context): array
    {
        $suggestions = [];
        $activeIntents = array_values(array_unique(array_filter(array_merge(
            $intents,
            array_slice($context['recent_intents'] ?? [], 0, 2)
        ))));

        if (in_array('orders', $activeIntents, true)) {
            $suggestions[] = ['label' => 'Previous orders', 'url' => '/orders'];
        }

        if (in_array('returns', $activeIntents, true)) {
            $suggestions[] = ['label' => 'Returns & refunds', 'url' => '/orders'];
        }

        if (in_array('contact', $activeIntents, true) || in_array('shipping', $activeIntents, true) || in_array('payment', $activeIntents, true)) {
            $suggestions[] = ['label' => 'Contact support', 'url' => '/pages/index.html#contactus'];
        }

        if (in_array('stock', $activeIntents, true) || in_array('recommendations', $activeIntents, true)) {
            $suggestions[] = ['label' => 'Browse products', 'url' => '/pages/ShopAll.html'];
        }

        if (Auth::check() && in_array('account', $activeIntents, true)) {
            $suggestions[] = ['label' => 'Profile info', 'url' => '/profile'];
        }

        return $suggestions;
    }

    private function isGenericOrderSupportRequest(string $message, array $intents): bool
    {
        if (! in_array('orders', $intents, true)) {
            return false;
        }

        $normalized = $this->normalizeKnownPhrase($message);

        foreach ([
            'i need help with an order',
            'i need help with order',
            'need help with an order',
            'need help with order',
            'help with an order',
            'help with order',
            'i need help with my order',
            'help with my order',
            'order help',
        ] as $phrase) {
            if (str_contains($normalized, $phrase)) {
                return true;
            }
        }

        return false;
    }

    private function appendIfMissing(string $reply, string $suffix): string
    {
        $normalizedReply = $this->normalizeKnownPhrase($reply);
        $normalizedSuffix = $this->normalizeKnownPhrase($suffix);

        if ($normalizedSuffix !== '' && str_contains($normalizedReply, trim($normalizedSuffix))) {
            return $reply;
        }

        if (str_contains($normalizedReply, 'previous orders') && str_contains($normalizedSuffix, 'previous orders')) {
            return $reply;
        }

        if (str_contains($normalizedReply, 'order history') && str_contains($normalizedSuffix, 'order history')) {
            return $reply;
        }

        return rtrim($reply) . $suffix;
    }
}
