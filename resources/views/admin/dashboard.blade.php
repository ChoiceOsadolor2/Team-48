<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <style>
        .admin-dashboard-page,
        .admin-dashboard-page * {
            font-weight: 400 !important;
        }

        .admin-dashboard-page .text-xs,
        .admin-dashboard-page .text-sm {
            font-size: 20px !important;
            line-height: 1.2 !important;
        }

        .admin-dashboard-page .text-lg {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-dashboard-page .text-xl,
        .admin-dashboard-page .text-2xl,
        .admin-dashboard-page .text-3xl,
        .admin-dashboard-page .text-4xl {
            font-size: 30px !important;
            line-height: 1.2 !important;
        }

        .admin-dashboard-page .stock-health-link {
            display: block;
            width: 180px;
            padding: 12px 18px;
            background: #000 !important;
            background-image: none !important;
            color: white !important;
            border: 1px solid #444 !important;
            border-radius: 14px;
            font-family: 'MiniPixel', sans-serif !important;
            font-size: 20px !important;
            font-weight: 100 !important;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.25s ease;
            position: relative;
            box-sizing: border-box;
            line-height: 1.2;
        }

        .admin-dashboard-page .stock-health-link::after {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-dashboard-page .stock-health-link:hover,
        .admin-dashboard-page .stock-health-link:focus-visible {
            background: #1d1d1d !important;
            transform: translateY(-2px);
            border-color: transparent !important;
            color: #fff !important;
            outline: none;
        }

        .admin-dashboard-page .stock-health-link:hover::after,
        .admin-dashboard-page .stock-health-link:focus-visible::after {
            opacity: 1;
        }

        .admin-dashboard-page .dashboard-stat-card {
            position: relative;
        }

        .admin-dashboard-page .dashboard-stat-card::after {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-dashboard-page .dashboard-stat-card:hover,
        .admin-dashboard-page .dashboard-stat-card:focus-visible {
            background: #1d1d1d !important;
            border-color: transparent !important;
            outline: none;
        }

        .admin-dashboard-page .dashboard-stat-card:hover::after,
        .admin-dashboard-page .dashboard-stat-card:focus-visible::after {
            opacity: 1;
        }

        .admin-dashboard-page .dashboard-stat-card .dashboard-stat-copy {
            color: #888 !important;
        }

        .admin-dashboard-page .activity-summary-shell {
            overflow: visible;
        }

        .admin-dashboard-page .activity-summary-toggle {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            width: 100%;
            padding: 18px 22px;
            background: #000;
            color: #fff;
            border: 1px solid #444;
            border-radius: 18px;
            font-family: 'MiniPixel', sans-serif !important;
            font-size: 30px !important;
            line-height: 1.2 !important;
            transition: all 0.25s ease;
            position: relative;
        }

        .admin-dashboard-page .activity-summary-toggle::-webkit-details-marker {
            display: none;
        }

        .admin-dashboard-page .activity-summary-toggle::after {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-dashboard-page .activity-summary-toggle:hover,
        .admin-dashboard-page .activity-summary-toggle:focus-visible {
            background: #1d1d1d;
            transform: translateY(-2px);
            border-color: transparent;
            outline: none;
        }

        .admin-dashboard-page .activity-summary-toggle:hover::after,
        .admin-dashboard-page .activity-summary-toggle:focus-visible::after {
            opacity: 1;
        }

        .admin-dashboard-page .activity-summary-chevron {
            position: absolute;
            right: 22px;
            font-size: 24px !important;
            line-height: 1 !important;
            color: #fff;
            transition: transform 0.2s ease;
        }

        .admin-dashboard-page .activity-summary-shell[open] .activity-summary-chevron {
            transform: rotate(180deg);
        }

        .admin-dashboard-page .activity-summary-content {
            margin-top: 16px;
        }

        .admin-dashboard-page .activity-summary-link-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            padding: 10px 14px;
            background: #000 !important;
            color: #fff !important;
            border: 1px solid #444 !important;
            border-radius: 14px;
            font-family: 'MiniPixel', sans-serif !important;
            font-size: 16px !important;
            line-height: 1.2 !important;
            text-decoration: none;
            position: relative;
            transition: all 0.25s ease;
        }

        .admin-dashboard-page .activity-summary-link-btn::after {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-dashboard-page .activity-summary-link-btn:hover,
        .admin-dashboard-page .activity-summary-link-btn:focus-visible {
            background: #1d1d1d !important;
            transform: translateY(-2px);
            border-color: transparent !important;
            outline: none;
        }

        .admin-dashboard-page .activity-summary-link-btn:hover::after,
        .admin-dashboard-page .activity-summary-link-btn:focus-visible::after {
            opacity: 1;
        }

        .admin-dashboard-page .activity-summary-content .rounded-2xl {
            padding: 14px !important;
        }

        .admin-dashboard-page .activity-summary-content .rounded-xl {
            padding: 10px 12px !important;
        }

        .admin-dashboard-page .activity-summary-content .text-sm,
        .admin-dashboard-page .activity-summary-content .text-xs {
            font-size: 16px !important;
            line-height: 1.25 !important;
        }

        .admin-dashboard-page .activity-summary-content h4,
        .admin-dashboard-page .activity-summary-content .font-semibold {
            font-size: 16px !important;
            line-height: 1.2 !important;
        }

    </style>

    <div class="admin-dashboard-page py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 dark:border-emerald-800/70 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="dashboard-stat-card block w-full sm:w-[calc(50%-0.5rem)] lg:w-[360px] min-h-[145px] rounded-3xl border border-[#444] bg-black p-4 text-white shadow-xl transition-transform duration-200 hover:-translate-y-1 hover:border-[#444]">
                    <p class="text-sm uppercase tracking-[0.2em] text-cyan-300">Users</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalUsers) }}</p>
                    <p class="dashboard-stat-copy mt-2 text-sm text-gray-300">Registered accounts on the platform.</p>
                </a>
                <a href="{{ route('admin.products.index') }}" class="dashboard-stat-card block w-full sm:w-[calc(50%-0.5rem)] lg:w-[360px] min-h-[145px] rounded-3xl border border-[#444] bg-black p-4 text-white shadow-xl transition-transform duration-200 hover:-translate-y-1 hover:border-[#444]">
                    <p class="text-sm uppercase tracking-[0.2em] text-emerald-300">Products</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalProducts) }}</p>
                    <p class="dashboard-stat-copy mt-2 text-sm text-gray-300">{{ $inStockProducts }} in stock, {{ $outOfStockProducts }} out of stock, {{ $lowStockProductCount }} low stock.</p>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="dashboard-stat-card block w-full sm:w-[calc(50%-0.5rem)] lg:w-[360px] min-h-[145px] rounded-3xl border border-[#444] bg-black p-4 text-white shadow-xl transition-transform duration-200 hover:-translate-y-1 hover:border-[#444]">
                    <p class="text-sm uppercase tracking-[0.2em] text-amber-300">Orders</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalOrders) }}</p>
                    <p class="dashboard-stat-copy mt-2 text-sm text-gray-300">{{ $processingOrders }} processing, {{ $completedOrders }} completed, {{ $cancelledOrders }} cancelled.</p>
                </a>
                <a href="{{ route('admin.revenue.index') }}" class="dashboard-stat-card block w-full sm:w-[calc(50%-0.5rem)] lg:w-[360px] min-h-[145px] rounded-3xl border border-[#444] bg-black p-4 text-white shadow-xl transition-transform duration-200 hover:-translate-y-1 hover:border-[#444]">
                    <p class="text-sm uppercase tracking-[0.2em] text-pink-300">Revenue</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalRevenue, 2) }} GBP</p>
                    <p class="dashboard-stat-copy mt-2 text-sm text-gray-300">Average order value {{ number_format($averageOrderValue, 2) }} GBP.</p>
                </a>
                <a href="{{ route('admin.refunds.index') }}" class="dashboard-stat-card block w-full sm:w-[calc(50%-0.5rem)] lg:w-[360px] min-h-[145px] rounded-3xl border border-[#444] bg-black p-4 text-white shadow-xl transition-transform duration-200 hover:-translate-y-1 hover:border-[#444]">
                    <p class="text-sm uppercase tracking-[0.2em] text-orange-300">Refunds</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($refundRequestCount) }}</p>
                    <p class="dashboard-stat-copy mt-2 text-sm text-gray-300">{{ $pendingRefundCount }} pending decisions in the queue.</p>
                </a>
                <a href="{{ route('admin.contact-queries.index') }}" class="dashboard-stat-card block w-full sm:w-[calc(50%-0.5rem)] lg:w-[360px] min-h-[145px] rounded-3xl border border-[#444] bg-black p-4 text-white shadow-xl transition-transform duration-200 hover:-translate-y-1 hover:border-[#444]">
                    <p class="text-sm uppercase tracking-[0.2em] text-sky-300">Contact queries</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($contactQueryCount) }}</p>
                    <p class="dashboard-stat-copy mt-2 text-sm text-gray-300">Open customer messages in the support inbox.</p>
                </a>
            </div>

            <div class="mb-8 grid grid-cols-1 xl:grid-cols-2 gap-5">
                <section class="rounded-3xl border border-[#444] bg-[#1d1d1f] p-5 shadow-xl xl:col-span-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white">Inventory Overview</h3>
                        </div>
                        <a href="{{ route('admin.products.create') }}" class="stock-health-link text-sm font-semibold">Add New Product</a>
                    </div>
                    <div class="mt-5 grid grid-cols-4 gap-3">
                        <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-3.5 text-white">
                            <p class="text-sm font-semibold">All stock</p>
                            <p class="mt-1.5 text-2xl font-bold">{{ $totalProducts }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-3.5 text-emerald-300">
                            <p class="text-sm font-semibold">Available</p>
                            <p class="mt-1.5 text-2xl font-bold">{{ $inStockProducts }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-3.5 text-rose-300">
                            <p class="text-sm font-semibold">Out of stock</p>
                            <p class="mt-1.5 text-2xl font-bold">{{ $outOfStockProducts }}</p>
                        </div>
                        <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-3.5 text-yellow-300">
                            <p class="text-sm font-semibold">Low stock</p>
                            <p class="mt-1.5 text-2xl font-bold">{{ $lowStockProducts->count() }}</p>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-sm font-semibold text-white">Low stock alerts</p>
                            <span class="text-xs" style="color: #888 !important;">5 units or fewer</span>
                        </div>
                        @if ($lowStockProducts->isEmpty())
                            <p class="rounded-2xl border border-[#444] bg-[#1d1d1f] px-4 py-3 text-sm text-gray-400">No products currently need urgent restocking.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($lowStockProducts as $product)
                                    <div class="flex items-center justify-between rounded-2xl border border-[#444] bg-[#1d1d1f] px-4 py-3">
                                        <span class="font-semibold text-gray-100" style="font-size: 20px !important; line-height: 1.2 !important;">{{ $product->name }}</span>
                                        <div class="flex items-center gap-3">
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800">{{ $product->stock }} left</span>
                                            <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="stock-health-link"
                                            >
                                                Restock
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="mt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-sm font-semibold text-white">Out of stock alerts</p>
                            <span class="text-xs" style="color: #888 !important;">0 units remaining</span>
                        </div>
                        @if ($outOfStockProductAlerts->isEmpty())
                            <p class="rounded-2xl border border-[#444] bg-[#1d1d1f] px-4 py-3 text-sm text-gray-400">No products are currently out of stock.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($outOfStockProductAlerts as $product)
                                    <div class="flex items-center justify-between rounded-2xl border border-[#444] bg-[#1d1d1f] px-4 py-3">
                                        <span class="font-semibold text-gray-100" style="font-size: 20px !important; line-height: 1.2 !important;">{{ $product->name }}</span>
                                        <a
                                            href="{{ route('admin.products.edit', $product) }}"
                                            class="stock-health-link"
                                        >
                                            Restock
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

            </div>

            <details class="activity-summary-shell mb-8">
                <summary class="activity-summary-toggle">
                    <span>Activity Summary</span>
                    <span class="activity-summary-chevron">v</span>
                </summary>

                <div class="activity-summary-content grid grid-cols-1 gap-5 xl:grid-cols-4">
                    <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-4 text-white">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-white">Recent orders</h4>
                            <a href="{{ route('admin.orders.index') }}" class="activity-summary-link-btn">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($recentOrders as $order)
                                <div class="rounded-xl border border-[#444] bg-[#1d1d1f] px-3 py-3 shadow-sm">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-white">#{{ $order->id }}</span>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200' : ($order->status === 'completed' || $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-[#888]">{{ $order->user?->name ?? 'Unknown customer' }}</p>
                                    <p class="mt-1 text-xs text-[#888]">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-[#888]">No recent orders yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-4 text-white">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-white">New users</h4>
                            <a href="{{ route('admin.users.index') }}" class="activity-summary-link-btn">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestUsers as $user)
                                <div class="rounded-xl border border-[#444] bg-[#1d1d1f] px-3 py-3 shadow-sm">
                                    <p class="font-semibold text-white">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs text-[#888]">{{ $user->email }}</p>
                                    <p class="mt-1 text-xs text-[#888]">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-[#888]">No recent user signups yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-4 text-white">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-white">Latest refund requests</h4>
                            <a href="{{ route('admin.refunds.index') }}" class="activity-summary-link-btn">View all</a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($latestRefundRequests as $refundRequest)
                                @php
                                    $refundBadgeClasses = match($refundRequest->status) {
                                        'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                                        'denied' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
                                        default => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                    };
                                @endphp
                                <div class="rounded-xl border border-[#444] bg-[#1d1d1f] px-3 py-3 shadow-sm">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="font-semibold text-white">Refund: {{ $refundRequest->user?->name ?? 'Unknown customer' }}</p>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $refundBadgeClasses }}">
                                            {{ ucfirst($refundRequest->status) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-[#888]">{{ \Illuminate\Support\Str::limit($refundRequest->orderItem?->product?->name ?? 'Deleted product', 30) }}</p>
                                    <p class="mt-1 text-xs text-[#888]">{{ $refundRequest->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach

                            @if ($latestRefundRequests->isEmpty())
                                <p class="text-sm text-[#888]">No refund requests yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-2xl border border-[#444] bg-[#1d1d1f] p-4 text-white">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-white">Latest contact queries</h4>
                            <a href="{{ route('admin.contact-queries.index') }}" class="activity-summary-link-btn">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestQueries as $query)
                                <div class="rounded-xl border border-[#444] bg-[#1d1d1f] px-3 py-3 shadow-sm">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="font-semibold text-white">{{ $query->name }}</p>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $query->resolved_at ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' }}">
                                            {{ $query->resolved_at ? 'Resolved' : 'Open' }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-[#888]">{{ \Illuminate\Support\Str::limit($query->subject, 34) }}</p>
                                    <p class="mt-1 text-xs text-[#888]">{{ $query->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-[#888]">No recent customer queries yet.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </details>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('admin.faqs.index') }}" class="dashboard-stat-card group relative rounded-3xl border border-[#444] bg-black p-6 text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-[#444] overflow-visible flex flex-col items-center text-center">
                    <h4 class="z-10 text-xl font-bold text-white mb-2">Chatbot FAQs</h4>
                    <p class="dashboard-stat-copy z-10 text-sm leading-relaxed">Add and update FAQ answers that power the smarter chatbot.</p>
                </a>

                <a href="{{ route('admin.discount-codes.index') }}" class="dashboard-stat-card group relative rounded-3xl border border-[#444] bg-black p-6 text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-[#444] overflow-visible flex flex-col items-center text-center">
                    <h4 class="z-10 text-xl font-bold text-white mb-2">Discount Codes</h4>
                    <p class="dashboard-stat-copy z-10 text-sm leading-relaxed">Create and manage percentage or fixed-amount promotions for checkout.</p>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}" class="dashboard-stat-card group relative rounded-3xl border border-[#444] bg-black p-6 text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-[#444] overflow-visible flex flex-col items-center text-center">
                    <h4 class="z-10 text-xl font-bold text-white mb-2">Audit Trail</h4>
                    <p class="dashboard-stat-copy z-10 text-sm leading-relaxed">Review a running history of important admin actions across the store.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
