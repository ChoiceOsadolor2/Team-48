<x-app-layout>
    <style>
        .admin-revenue-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-revenue-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        html[data-theme="dark"] .admin-revenue-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-revenue-page .page-intro-copy {
            color: #9ca3af !important;
        }

        @media (min-width: 768px) {
            .admin-revenue-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }
    </style>
    <div class="admin-revenue-page py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="page-intro">
            <div>
                <h1 class="page-intro-title flex items-center gap-3">
                    <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18"></path>
                        <path d="m7 14 3-3 3 2 4-5"></path>
                    </svg>
                    <span>Revenue Overview</span>
                </h1>
                <p class="page-intro-copy">Gross revenue excludes cancelled orders. Refund totals reflect approved refund requests only.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-500">Gross revenue</p>
                <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($grossRevenue, 2) }} GBP</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">All non-cancelled orders combined.</p>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs uppercase tracking-[0.2em] text-sky-500">Net revenue</p>
                <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($netRevenue, 2) }} GBP</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Gross revenue minus approved refunds.</p>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs uppercase tracking-[0.2em] text-rose-500">Approved refunds</p>
                <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($approvedRefundValue, 2) }} GBP</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $approvedRefundCount }} refund request{{ $approvedRefundCount === 1 ? '' : 's' }} approved.</p>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs uppercase tracking-[0.2em] text-amber-500">Average order</p>
                <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($averageOrderValue, 2) }} GBP</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Average non-cancelled order value.</p>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs uppercase tracking-[0.2em] text-violet-500">Order mix</p>
                <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($completedOrderCount) }}</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $processingOrderCount }} processing, {{ $completedOrderCount }} completed or delivered.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.7fr,1fr]">
            <section class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Revenue trend</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Last 6 months of gross revenue, refunds, and net revenue.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                        Accurate to {{ now()->format('d M Y') }}
                    </span>
                </div>

                <div class="mt-6">
                    <div class="flex h-[280px] items-end gap-4">
                        @foreach ($monthlyRevenue as $month)
                            @php
                                $grossHeight = max(8, ($month['gross'] / $graphMax) * 210);
                                $refundHeight = max(4, ($month['refunds'] / $graphMax) * 210);
                                $netHeight = max(8, (max($month['net'], 0) / $graphMax) * 210);
                            @endphp
                            <div class="flex flex-1 flex-col items-center gap-3">
                                <div class="flex h-[220px] w-full items-end justify-center gap-2">
                                    <div class="w-full max-w-[28px] rounded-t-2xl bg-emerald-500/85" style="height: {{ $grossHeight }}px;" title="Gross {{ number_format($month['gross'], 2) }} GBP"></div>
                                    <div class="w-full max-w-[28px] rounded-t-2xl bg-rose-400/80" style="height: {{ $refundHeight }}px;" title="Refunds {{ number_format($month['refunds'], 2) }} GBP"></div>
                                    <div class="w-full max-w-[28px] rounded-t-2xl bg-sky-500/85" style="height: {{ $netHeight }}px;" title="Net {{ number_format($month['net'], 2) }} GBP"></div>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $month['short_label'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $month['orders'] }} order{{ $month['orders'] === 1 ? '' : 's' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex flex-wrap gap-4 text-xs font-semibold text-gray-600 dark:text-gray-300">
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-emerald-500"></span>Gross</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-rose-400"></span>Refunds</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-sky-500"></span>Net</span>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Monthly breakdown</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Direct figures behind the graph.</p>

                <div class="mt-5 space-y-3">
                    @foreach ($monthlyRevenue as $month)
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4 dark:border-gray-700 dark:bg-gray-900/50">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $month['label'] }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $month['orders'] }} order{{ $month['orders'] === 1 ? '' : 's' }}</p>
                                </div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($month['net'], 2) }} GBP</p>
                            </div>
                            <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Gross</p>
                                    <p class="mt-1 font-semibold text-emerald-600 dark:text-emerald-300">{{ number_format($month['gross'], 2) }} GBP</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Refunds</p>
                                    <p class="mt-1 font-semibold text-rose-600 dark:text-rose-300">{{ number_format($month['refunds'], 2) }} GBP</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Net</p>
                                    <p class="mt-1 font-semibold text-sky-600 dark:text-sky-300">{{ number_format($month['net'], 2) }} GBP</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
