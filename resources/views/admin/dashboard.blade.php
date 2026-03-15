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
    </style>

    <div class="admin-dashboard-page py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10 flex items-center justify-between bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-800 rounded-3xl p-10 shadow-2xl text-white transform transition-all hover:scale-[1.01] duration-300">
                <div class="max-w-2xl">
                    <h3 class="text-4xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                        Welcome back, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-indigo-100 text-lg opacity-90 leading-relaxed">
                        Here's your command center. Manage your users, keep track of inventory, and process orders all in one place.
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 p-5 rounded-full backdrop-blur-sm">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                <div class="rounded-3xl border border-white/10 bg-black/70 p-5 text-white shadow-xl">
                    <p class="text-sm uppercase tracking-[0.2em] text-cyan-300">Users</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalUsers) }}</p>
                    <p class="mt-2 text-sm text-gray-300">Registered accounts on the platform.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-black/70 p-5 text-white shadow-xl">
                    <p class="text-sm uppercase tracking-[0.2em] text-emerald-300">Products</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalProducts) }}</p>
                    <p class="mt-2 text-sm text-gray-300">{{ $inStockProducts }} in stock, {{ $outOfStockProducts }} out of stock.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-black/70 p-5 text-white shadow-xl">
                    <p class="text-sm uppercase tracking-[0.2em] text-amber-300">Orders</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalOrders) }}</p>
                    <p class="mt-2 text-sm text-gray-300">{{ $processingOrders }} processing, {{ $completedOrders }} completed.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-black/70 p-5 text-white shadow-xl">
                    <p class="text-sm uppercase tracking-[0.2em] text-pink-300">Revenue</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($totalRevenue, 2) }} GBP</p>
                    <p class="mt-2 text-sm text-gray-300">Average order value {{ number_format($averageOrderValue, 2) }} GBP.</p>
                </div>
            </div>

            <div class="mb-8 grid grid-cols-1 xl:grid-cols-3 gap-5">
                <section class="rounded-3xl border border-gray-200 bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800 xl:col-span-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-gray-400">Stock health</p>
                            <h3 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">Inventory snapshot</h3>
                        </div>
                        <a href="{{ route('admin.products.stock') }}" class="text-sm font-semibold text-cyan-600 hover:text-cyan-500">View stock</a>
                    </div>
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-emerald-50 p-3.5 text-emerald-900">
                            <p class="text-sm font-semibold">Available</p>
                            <p class="mt-1.5 text-2xl font-bold">{{ $inStockProducts }}</p>
                        </div>
                        <div class="rounded-2xl bg-rose-50 p-3.5 text-rose-900">
                            <p class="text-sm font-semibold">Out of stock</p>
                            <p class="mt-1.5 text-2xl font-bold">{{ $outOfStockProducts }}</p>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Low stock alerts</p>
                            <span class="text-xs text-gray-400">5 units or fewer</span>
                        </div>
                        @if ($lowStockProducts->isEmpty())
                            <p class="rounded-2xl bg-gray-50 px-4 py-3 text-sm text-gray-500 dark:bg-gray-900 dark:text-gray-400">No products currently need urgent restocking.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($lowStockProducts as $product)
                                    <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-900">
                                        <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $product->name }}</span>
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800">{{ $product->stock }} left</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800 xl:col-span-1">
                    <p class="text-sm uppercase tracking-[0.2em] text-gray-400">Order mix</p>
                    <h3 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">Current fulfilment status</h3>
                    <div class="mt-5 space-y-3">
                        <div class="rounded-2xl bg-amber-50 p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-amber-900">Processing</span>
                                <span class="text-xl font-bold text-amber-900">{{ $processingOrders }}</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-emerald-900">Completed</span>
                                <span class="text-xl font-bold text-emerald-900">{{ $completedOrders }}</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-rose-50 p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-rose-900">Cancelled</span>
                                <span class="text-xl font-bold text-rose-900">{{ $cancelledOrders }}</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-sky-50 p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-sky-900">Contact queries</span>
                                <span class="text-xl font-bold text-sky-900">{{ $contactQueryCount }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800 xl:col-span-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-gray-400">Quick actions</p>
                            <h3 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">Jump straight into key admin work</h3>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-cyan-600 hover:text-cyan-500">Open inventory</a>
                    </div>
                    <div class="mt-5 grid grid-cols-1 gap-2.5">
                        <a href="{{ route('admin.products.create') }}" class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 transition hover:-translate-y-0.5 hover:shadow-md dark:border-cyan-800/60 dark:bg-cyan-900/20">
                            <p class="text-xs uppercase tracking-[0.18em] text-cyan-700 dark:text-cyan-300">Add product</p>
                            <p class="mt-1.5 text-sm font-semibold text-gray-900 dark:text-white">Create a new catalogue item</p>
                        </a>

                        <a href="{{ route('admin.products.index', ['stock' => 'low_stock']) }}" class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 transition hover:-translate-y-0.5 hover:shadow-md dark:border-amber-800/60 dark:bg-amber-900/20">
                            <p class="text-xs uppercase tracking-[0.18em] text-amber-700 dark:text-amber-300">Low stock</p>
                            <p class="mt-1.5 text-sm font-semibold text-gray-900 dark:text-white">Review products that need restocking</p>
                        </a>

                        <a href="{{ route('admin.contact-queries.index', ['status' => 'unresolved']) }}" class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 transition hover:-translate-y-0.5 hover:shadow-md dark:border-sky-800/60 dark:bg-sky-900/20">
                            <p class="text-xs uppercase tracking-[0.18em] text-sky-700 dark:text-sky-300">Open queries</p>
                            <p class="mt-1.5 text-sm font-semibold text-gray-900 dark:text-white">View unresolved customer messages</p>
                        </a>

                        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 transition hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-800/60 dark:bg-emerald-900/20">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Processing orders</p>
                            <p class="mt-1.5 text-sm font-semibold text-gray-900 dark:text-white">Open the orders still being fulfilled</p>
                        </a>

                        <a href="{{ route('admin.return-requests.index', ['status' => 'pending']) }}" class="rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 transition hover:-translate-y-0.5 hover:shadow-md dark:border-violet-800/60 dark:bg-violet-900/20">
                            <p class="text-xs uppercase tracking-[0.18em] text-violet-700 dark:text-violet-300">Pending returns</p>
                            <p class="mt-1.5 text-sm font-semibold text-gray-900 dark:text-white">{{ $pendingReturnRequestCount }} waiting for review</p>
                        </a>

                    </div>
                </section>
            </div>

            <section class="mb-8 rounded-3xl border border-gray-200 bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-gray-400">Activity summary</p>
                        <h3 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">Latest movement across the store</h3>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-200">Live snapshot</span>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-4">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/60">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">Recent orders</h4>
                            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-500">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($recentOrders as $order)
                                <div class="rounded-xl bg-white px-3 py-3 shadow-sm dark:bg-gray-800">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</span>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200' : ($order->status === 'completed' || $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $order->user?->name ?? 'Unknown customer' }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent orders yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/60">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">New users</h4>
                            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-500">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestUsers as $user)
                                <div class="rounded-xl bg-white px-3 py-3 shadow-sm dark:bg-gray-800">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent user signups yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/60">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">New products</h4>
                            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-500">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestProducts as $product)
                                <div class="rounded-xl bg-white px-3 py-3 shadow-sm dark:bg-gray-800">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $product->category->name ?? 'No category' }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $product->stock }} in stock • {{ $product->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recently added products yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/60">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">Latest queries</h4>
                            <a href="{{ route('admin.contact-queries.index') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-500">View all</a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($latestQueries as $query)
                                <div class="rounded-xl bg-white px-3 py-3 shadow-sm dark:bg-gray-800">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $query->name }}</p>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $query->resolved_at ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' }}">
                                            {{ $query->resolved_at ? 'Resolved' : 'Open' }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($query->subject, 34) }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $query->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent customer queries yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6">
                <a href="{{ route('admin.users.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-blue-100 dark:bg-blue-900/40 p-4 rounded-2xl mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-xl font-bold text-gray-900 dark:text-white mb-2">Manage Users</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">View, edit, or safely remove user accounts from the platform.</p>
                </a>

                <a href="{{ route('admin.products.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/10 dark:to-teal-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-emerald-100 dark:bg-emerald-900/40 p-4 rounded-2xl mb-5 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-xl font-bold text-gray-900 dark:text-white mb-2">Inventory</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Manage stock levels, easily add new products, and update pricing.</p>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-purple-100 dark:bg-purple-900/40 p-4 rounded-2xl mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-xl font-bold text-gray-900 dark:text-white mb-2">View Orders</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Process customer orders, view full details, and track order fulfilments.</p>
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-sky-50 dark:from-cyan-900/10 dark:to-sky-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-cyan-100 dark:bg-cyan-900/40 p-4 rounded-2xl mb-5 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M7 4h10a2 2 0 012 2v12l-4-3H7a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-xl font-bold text-gray-900 dark:text-white mb-2">Chatbot FAQs</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Add and update FAQ answers that power the smarter chatbot.</p>
                </a>

                <a href="{{ route('admin.contact-queries.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-50 to-blue-50 dark:from-sky-900/10 dark:to-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-sky-100 dark:bg-sky-900/40 p-4 rounded-2xl mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-8 h-8 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.8L3 20l1.195-3.586C3.44 15.166 3 13.635 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-xl font-bold text-gray-900 dark:text-white mb-2">Contact Queries</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Review messages submitted through the Contact Us forms and keep support requests organised.</p>
                </a>

                <a href="{{ route('admin.return-requests.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-50 to-fuchsia-50 dark:from-violet-900/10 dark:to-fuchsia-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-violet-100 dark:bg-violet-900/40 p-4 rounded-2xl mb-5 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h3m5 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V18a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-xl font-bold text-gray-900 dark:text-white mb-2">Returns & Refunds</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Review after-sales requests, approve or decline them, and keep an audit trail with support notes.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
