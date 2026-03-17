<x-app-layout>
    <style>
        .admin-return-request-show-page .request-card {
            background: #1d1d1d;
            border-color: #3e3e3e;
        }

        .admin-return-request-show-page .request-soft {
            background: linear-gradient(180deg, #2a2a2a 0%, #242424 100%);
            border: 1px solid #383838;
            color: #f9fafb !important;
        }

        .admin-return-request-show-page .request-text {
            color: #f9fafb !important;
        }

        .admin-return-request-show-page .request-muted {
            color: #9ca3af !important;
        }

        .admin-return-request-show-page .request-field {
            background: #262626 !important;
            border-color: #444 !important;
            color: #f9fafb !important;
        }

        .admin-return-request-show-page .request-field::placeholder {
            color: #9ca3af !important;
        }
    </style>

    <div class="admin-return-request-show-page py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @php
            $statusClasses = match ($returnRequest->status) {
                'approved' => 'bg-emerald-100 text-emerald-800',
                'declined' => 'bg-rose-100 text-rose-800',
                default => 'bg-amber-100 text-amber-800',
            };
        @endphp

        <div class="request-card flex flex-col gap-4 rounded-3xl border p-6 shadow-sm lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="request-muted text-sm uppercase tracking-[0.18em]">Return Request</p>
                <h1 class="request-text mt-2 text-3xl font-bold">Request #{{ $returnRequest->id }}</h1>
                <p class="request-muted mt-2 max-w-2xl text-[0.98rem]">
                    Review the request, confirm the customer context, and record a clear outcome for the support team.
                </p>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                        {{ ucfirst($returnRequest->status) }}
                    </span>
                    <span class="request-muted text-sm">Submitted {{ $returnRequest->created_at->format('d M Y, H:i') }}</span>
                    @if ($returnRequest->reviewed_at)
                        <span class="request-muted text-sm">Reviewed {{ $returnRequest->reviewed_at->format('d M Y, H:i') }}</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.return-requests.index') }}" class="admin-btn admin-btn--secondary">
                Back to Requests
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="request-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="request-text text-lg font-semibold">Customer and order details</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="request-soft rounded-2xl p-4">
                            <p class="request-muted text-xs uppercase tracking-[0.16em]">Customer</p>
                            <p class="request-text mt-2 font-semibold">{{ $returnRequest->user?->name ?? 'Unknown customer' }}</p>
                            <p class="request-muted mt-1 text-sm">{{ $returnRequest->user?->email }}</p>
                        </div>
                        <div class="request-soft rounded-2xl p-4">
                            <p class="request-muted text-xs uppercase tracking-[0.16em]">Order</p>
                            <p class="request-text mt-2 font-semibold">VX-{{ $returnRequest->order_id }}</p>
                            <p class="request-muted mt-1 text-sm">Item #{{ $returnRequest->order_item_id }}</p>
                        </div>
                        <div class="request-soft rounded-2xl p-4">
                            <p class="request-muted text-xs uppercase tracking-[0.16em]">Product</p>
                            <p class="request-text mt-2 font-semibold">{{ $returnRequest->product?->name ?? 'Unknown product' }}</p>
                            <p class="request-muted mt-1 text-sm">{{ ucfirst($returnRequest->request_type) }} request</p>
                        </div>
                        <div class="request-soft rounded-2xl p-4">
                            <p class="request-muted text-xs uppercase tracking-[0.16em]">Reviewed by</p>
                            <p class="request-text mt-2 font-semibold">{{ $returnRequest->reviewedBy?->name ?? 'Not reviewed yet' }}</p>
                            <p class="request-muted mt-1 text-sm">{{ $returnRequest->reviewed_at ? $returnRequest->reviewed_at->diffForHumans() : 'Awaiting review' }}</p>
                        </div>
                    </div>
                </div>

                <div class="request-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="request-text text-lg font-semibold">Customer reason</h2>
                    <div class="request-soft mt-4 rounded-2xl p-4 leading-7 whitespace-pre-line">
                        {{ $returnRequest->reason }}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="request-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="request-text text-lg font-semibold">Decision</h2>
                    <p class="request-muted mt-2 text-sm">Approve or decline the request and leave clear internal notes for the support team.</p>

                    <form method="POST" action="{{ route('admin.return-requests.update-status', $returnRequest) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="request-text mb-1 block text-sm font-semibold">Status</label>
                            <select name="status" class="request-field w-full rounded-xl border px-3 py-2.5 text-sm">
                                <option value="pending" @selected($returnRequest->status === 'pending')>Pending review</option>
                                <option value="approved" @selected($returnRequest->status === 'approved')>Approved</option>
                                <option value="declined" @selected($returnRequest->status === 'declined')>Declined</option>
                            </select>
                        </div>

                        <div>
                            <label class="request-text mb-1 block text-sm font-semibold">Admin notes</label>
                            <textarea name="admin_notes" rows="8" class="request-field w-full rounded-xl border px-3 py-2.5 text-sm" placeholder="Add internal notes or explain why the request was approved or declined.">{{ old('admin_notes', $returnRequest->admin_notes) }}</textarea>
                        </div>

                        <button type="submit" class="admin-btn admin-btn--primary w-full">
                            Save Decision
                        </button>
                    </form>
                </div>

                @if ($returnRequest->admin_notes)
                    <div class="request-card rounded-3xl border p-6 shadow-sm">
                        <h2 class="request-text text-lg font-semibold">Latest admin notes</h2>
                        <div class="request-soft mt-4 rounded-2xl p-4 leading-7 whitespace-pre-line">
                            {{ $returnRequest->admin_notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
