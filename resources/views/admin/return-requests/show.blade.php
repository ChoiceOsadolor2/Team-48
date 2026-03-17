<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @php
            $statusClasses = match ($returnRequest->status) {
                'approved' => 'bg-emerald-100 text-emerald-800',
                'declined' => 'bg-rose-100 text-rose-800',
                default => 'bg-amber-100 text-amber-800',
            };
        @endphp

        <div class="flex flex-col gap-4 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.18em] text-gray-400">Return Request</p>
                <h1 class="mt-2 text-3xl font-bold text-gray-900">Request #{{ $returnRequest->id }}</h1>
                <p class="mt-2 max-w-2xl text-[0.98rem] text-gray-500">
                    Review the request, confirm the customer context, and record a clear outcome for the support team.
                </p>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                        {{ ucfirst($returnRequest->status) }}
                    </span>
                    <span class="text-sm text-gray-500">Submitted {{ $returnRequest->created_at->format('d M Y, H:i') }}</span>
                    @if ($returnRequest->reviewed_at)
                        <span class="text-sm text-gray-500">Reviewed {{ $returnRequest->reviewed_at->format('d M Y, H:i') }}</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.return-requests.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300">
                Back to Requests
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Customer and order details</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-gray-50 p-4">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Customer</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $returnRequest->user?->name ?? 'Unknown customer' }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $returnRequest->user?->email }}</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 p-4">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Order</p>
                            <p class="mt-2 font-semibold text-gray-900">VX-{{ $returnRequest->order_id }}</p>
                            <p class="mt-1 text-sm text-gray-500">Item #{{ $returnRequest->order_item_id }}</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 p-4">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Product</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $returnRequest->product?->name ?? 'Unknown product' }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ ucfirst($returnRequest->request_type) }} request</p>
                        </div>
                        <div class="rounded-2xl bg-gray-50 p-4">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Reviewed by</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $returnRequest->reviewedBy?->name ?? 'Not reviewed yet' }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $returnRequest->reviewed_at ? $returnRequest->reviewed_at->diffForHumans() : 'Awaiting review' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Customer reason</h2>
                    <div class="mt-4 rounded-2xl bg-gray-50 p-4 text-gray-700 leading-7 whitespace-pre-line">
                        {{ $returnRequest->reason }}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Decision</h2>
                    <p class="mt-2 text-sm text-gray-500">Approve or decline the request and leave clear internal notes for the support team.</p>

                    <form method="POST" action="{{ route('admin.return-requests.update-status', $returnRequest) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="pending" @selected($returnRequest->status === 'pending')>Pending review</option>
                                <option value="approved" @selected($returnRequest->status === 'approved')>Approved</option>
                                <option value="declined" @selected($returnRequest->status === 'declined')>Declined</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Admin notes</label>
                            <textarea name="admin_notes" rows="8" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm" placeholder="Add internal notes or explain why the request was approved or declined.">{{ old('admin_notes', $returnRequest->admin_notes) }}</textarea>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-500">
                            Save Decision
                        </button>
                    </form>
                </div>

                @if ($returnRequest->admin_notes)
                    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Latest admin notes</h2>
                        <div class="mt-4 rounded-2xl bg-gray-50 p-4 text-gray-700 leading-7 whitespace-pre-line">
                            {{ $returnRequest->admin_notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
