<x-app-layout>
    <style>
        .admin-discount-codes-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-discount-codes-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        html[data-theme="dark"] .admin-discount-codes-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-discount-codes-page .page-intro-copy {
            color: #9ca3af !important;
        }

        @media (min-width: 768px) {
            .admin-discount-codes-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }
    </style>

    <div class="admin-discount-codes-page py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="page-intro">
                <div>
                    <h1 class="page-intro-title">Discount Codes</h1>
                    <p class="page-intro-copy">Create and manage promo codes from one admin hub.</p>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Discount code management</h3>
                </div>
                <a href="{{ route('admin.discount-codes.create') }}" class="rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-cyan-500">
                    + Add Discount Code
                </a>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.discount-codes.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr,240px,auto] md:items-end">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Search codes</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search }}"
                                class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm uppercase"
                                placeholder="Search by code..."
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All codes</option>
                                <option value="active" @selected($status === 'active')>Active</option>
                                <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.discount-codes.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Promo code library</h3>
                        <p class="text-sm text-gray-500">Track availability, windows, and usage in one place.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                        Showing {{ $discountCodes->firstItem() ?? 0 }}-{{ $discountCodes->lastItem() ?? 0 }} of {{ $discountCodes->total() }}
                    </span>
                </div>

                @if ($discountCodes->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500">
                        No discount codes matched the current filters.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500">
                                    <th class="px-5 py-4 font-semibold">Code</th>
                                    <th class="px-5 py-4 font-semibold">Offer</th>
                                    <th class="px-5 py-4 font-semibold">Type</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 font-semibold">Usage</th>
                                    <th class="px-5 py-4 font-semibold">Window</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($discountCodes as $discountCode)
                                    @php
                                        $label = $discountCode->availabilityLabel();
                                        $statusClasses = match($label) {
                                            'Active' => 'bg-emerald-100 text-emerald-800',
                                            'Inactive' => 'bg-gray-100 text-gray-700',
                                            'Expired' => 'bg-rose-100 text-rose-800',
                                            'Scheduled' => 'bg-sky-100 text-sky-800',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <tr class="transition hover:bg-gray-50/80">
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                                {{ $discountCode->code }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $discountCode->type === 'percentage' ? rtrim(rtrim(number_format($discountCode->value, 2), '0'), '.') . '%' : '£' . number_format($discountCode->value, 2) }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                {{ $discountCode->type === 'percentage' ? 'Percentage off' : 'Fixed amount off' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-gray-600">
                                            {{ $discountCode->used_count }} / {{ $discountCode->usage_limit ?? '∞' }}
                                        </td>
                                        <td class="px-5 py-4 text-gray-600">
                                            <div>{{ $discountCode->starts_at?->format('d M Y') ?? 'Starts immediately' }}</div>
                                            <div class="mt-1 text-xs text-gray-500">{{ $discountCode->ends_at?->format('d M Y') ?? 'No end date' }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.discount-codes.edit', $discountCode) }}" class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50">Edit</a>
                                                <form action="{{ route('admin.discount-codes.destroy', $discountCode) }}" method="POST" onsubmit="return confirm('Delete this discount code?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($discountCodes->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $discountCodes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
