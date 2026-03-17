<x-app-layout>
    @php
        $statusClasses = $contactQuery->resolved_at
            ? 'bg-emerald-100 text-emerald-800'
            : 'bg-amber-100 text-amber-800';
    @endphp

    <style>
        .admin-contact-query-show-page .query-card {
            background: #1d1d1d;
            border-color: #3e3e3e;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
        }

        .admin-contact-query-show-page .query-soft {
            background: linear-gradient(180deg, #2a2a2a 0%, #242424 100%);
            border: 1px solid #383838;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
        }

        .admin-contact-query-show-page .query-text {
            color: #f9fafb !important;
        }

        .admin-contact-query-show-page .query-muted {
            color: #9ca3af !important;
        }

        .admin-contact-query-show-page .query-toggle-wrap {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: 56px;
            padding: 0 16px;
            border-radius: 18px;
            border: 1px solid #383838;
            background: linear-gradient(180deg, #2a2a2a 0%, #242424 100%);
        }

        .admin-contact-query-show-page .query-toggle-label {
            color: #f9fafb;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .admin-contact-query-show-page .query-status-badge--resolved {
            background: #d1fae5;
            color: #065f46 !important;
        }

        .admin-contact-query-show-page .query-status-badge--unresolved {
            background: #fef3c7;
            color: #92400e !important;
        }

        .admin-contact-query-show-page .query-toggle-btn {
            border: 0;
            background: transparent;
            padding: 0;
            line-height: 1;
            cursor: pointer;
        }

        html[data-theme="dark"] .admin-contact-query-show-page .query-soft {
            background: linear-gradient(180deg, #2a2a2a 0%, #242424 100%);
            border-color: #383838;
        }

        html[data-theme="dark"] .admin-contact-query-show-page .query-text {
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-contact-query-show-page .query-muted {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-contact-query-show-page .query-toggle-wrap {
            border-color: #383838;
            background: linear-gradient(180deg, #2a2a2a 0%, #242424 100%);
        }

        html[data-theme="dark"] .admin-contact-query-show-page .query-toggle-label {
            color: #f9fafb;
        }
    </style>

    <div class="admin-contact-query-show-page py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="query-card flex flex-col gap-4 rounded-3xl border p-6 shadow-sm lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="query-muted text-sm uppercase tracking-[0.18em]">Contact Query</p>
                <h1 class="query-text mt-2 text-3xl font-bold">Query #{{ $contactQuery->id }}</h1>
                <p class="query-muted mt-2 text-[0.98rem]">
                    Review the customer message, update support status, and keep internal guidance consistent.
                </p>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                        {{ $contactQuery->resolved_at ? 'Resolved' : 'Pending review' }}
                    </span>
                    <span class="query-muted text-sm">Submitted {{ $contactQuery->created_at->format('d M Y, H:i') }}</span>
                    @if ($contactQuery->resolved_at)
                        <span class="query-muted text-sm">Resolved {{ $contactQuery->resolved_at->format('d M Y, H:i') }}</span>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.contact-queries.index') }}" class="admin-btn admin-btn--secondary">
                    Back to Queries
                </a>

                <form method="POST" action="{{ route('admin.contact-queries.toggle', $contactQuery) }}" class="query-toggle-wrap">
                    @csrf
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $contactQuery->resolved_at ? 'query-status-badge--resolved' : 'query-status-badge--unresolved' }}">
                        {{ $contactQuery->resolved_at ? 'Resolved' : 'Unresolved' }}
                    </span>
                    <button type="submit" class="query-toggle-btn" aria-label="Toggle resolved status">
                        <span class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors {{ $contactQuery->resolved_at ? 'bg-emerald-500' : 'bg-gray-300' }}">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition {{ $contactQuery->resolved_at ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </span>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.contact-queries.destroy', $contactQuery) }}"
                      onsubmit="return confirm('Delete this contact query?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn--danger">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="query-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="query-text text-lg font-semibold">Customer details</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="query-soft rounded-2xl p-4">
                            <p class="query-muted text-xs uppercase tracking-[0.16em]">Name</p>
                            <p class="query-text mt-2 font-semibold">{{ $contactQuery->name }}</p>
                        </div>
                        <div class="query-soft rounded-2xl p-4">
                            <p class="query-muted text-xs uppercase tracking-[0.16em]">Email</p>
                            <p class="query-text mt-2 font-semibold">{{ $contactQuery->email }}</p>
                        </div>
                        <div class="query-soft rounded-2xl p-4 md:col-span-2">
                            <p class="query-muted text-xs uppercase tracking-[0.16em]">Subject</p>
                            <p class="query-text mt-2 font-semibold">{{ $contactQuery->subject }}</p>
                        </div>
                    </div>
                </div>

                <div class="query-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="query-text text-lg font-semibold">Message</h2>
                    <div class="query-soft query-text mt-4 rounded-2xl p-4 leading-7 whitespace-pre-line break-words">
                        {{ $contactQuery->message }}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="query-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="query-text text-lg font-semibold">Status overview</h2>
                    <div class="mt-4 space-y-3">
                        <div class="query-soft rounded-2xl p-4">
                            <p class="query-muted text-xs uppercase tracking-[0.16em]">Current status</p>
                            <p class="query-text mt-2 font-semibold">{{ $contactQuery->resolved_at ? 'Resolved' : 'Pending review' }}</p>
                        </div>

                        <div class="query-soft rounded-2xl p-4">
                            <p class="query-muted text-xs uppercase tracking-[0.16em]">Submitted</p>
                            <p class="query-text mt-2 font-semibold">{{ $contactQuery->created_at->format('d M Y, H:i') }}</p>
                        </div>

                        <div class="query-soft rounded-2xl p-4">
                            <p class="query-muted text-xs uppercase tracking-[0.16em]">Resolved at</p>
                            <p class="query-text mt-2 font-semibold">
                                {{ $contactQuery->resolved_at ? $contactQuery->resolved_at->format('d M Y, H:i') : 'Not resolved yet' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="query-card rounded-3xl border p-6 shadow-sm">
                    <h2 class="query-text text-lg font-semibold">Support guidance</h2>
                    <div class="query-soft query-text mt-4 rounded-2xl p-4 leading-7">
                        Review the customer's message, mark it resolved once action has been taken, and delete it only if it is no longer needed for support history.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
