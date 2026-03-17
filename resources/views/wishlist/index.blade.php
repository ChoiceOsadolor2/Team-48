<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/wishlist.css?v=4') }}" />

    <div class="wishlist-main w-full max-w-6xl mx-auto">
        <section class="wishlist-shell">
            <div class="wishlist-header">
                <h1 class="wishlist-title">Wishlist</h1>
            </div>

            <div id="wishlist_error" class="wishlist-message wishlist-message--error" hidden></div>
            <div id="wishlist_empty" class="wishlist-message" hidden>Your wishlist is empty.</div>

            <section id="wishlist_grid" class="wishlist-grid"></section>
        </section>
    </div>

    <script src="{{ asset('scripts/wishlist.js?v=4') }}"></script>
</x-app-layout>
