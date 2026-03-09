<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Your Cart</h1>

            @if (session('stock_error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                    {{ session('stock_error') }}
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($items->count() === 0)
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
                    <p class="text-gray-500 mb-8">Looks like you haven't added any games yet.</p>
                    <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                        Continue Shopping
                    </a>
                </div>
            @else
                <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                    
                    <div class="lg:col-span-7 xl:col-span-8">
                        <ul role="list" class="space-y-6">
                            @foreach ($items as $item)
                                <li class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex py-6 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex-shrink-0 relative overflow-hidden rounded-xl bg-gray-100 w-24 h-32 sm:w-32 sm:h-40">
                                        @if($item['product']->image)
                                            <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-center object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                        <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                            <div>
                                                <div class="flex justify-between">
                                                    <h3 class="text-lg font-bold text-gray-900">
                                                        <a href="{{ route('products.show', $item['product']->slug ?? $item['product']->id) }}" class="hover:text-indigo-600 transistion-colors">{{ $item['product']->name }}</a>
                                                    </h3>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500">{{ $item['product']->platform ?? 'Unknown Platform' }}</p>
                                                <p class="mt-1 text-base font-medium text-gray-900">£{{ number_format($item['product']->price, 2) }}</p>
                                            </div>

                                            <div class="mt-4 sm:mt-0 sm:pr-9">
                                                <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center space-x-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <label for="quantity-{{ $item['product']->id }}" class="sr-only">Quantity, {{ $item['product']->name }}</label>
                                                    <div class="flex items-center border border-gray-300 rounded-lg bg-gray-50 overflow-hidden">
                                                        <input type="number" id="quantity-{{ $item['product']->id }}" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border-none focus:ring-0 p-2 bg-transparent font-medium text-gray-900">
                                                    </div>
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold tracking-wide uppercase transition-colors">Update</button>
                                                </form>

                                                <div class="absolute top-0 right-0">
                                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="-m-2 p-2 inline-flex text-gray-400 hover:text-red-500 transition-colors">
                                                            <span class="sr-only">Remove</span>
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-4 flex text-sm text-gray-700 space-x-2">
                                            <span class="font-bold">Subtotal:</span>
                                            <span class="text-gray-900 font-medium tracking-tight">£{{ number_format($item['subtotal'], 2) }}</span>
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="mt-8 flex justify-end">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-800 transition-colors group">
                                    <svg class="mr-2 h-4 w-4 text-red-500 group-hover:text-red-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Clear Entire Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-10 lg:mt-0 lg:col-span-5 xl:col-span-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                        <dl class="space-y-4 text-sm text-gray-600">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <dt>Subtotal</dt>
                                <dd class="text-gray-900 font-medium">£{{ number_format($total, 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <dt class="flex items-center">
                                    Shipping estimate
                                </dt>
                                <dd class="text-gray-900 font-medium">Calculated at checkout</dd>
                            </div>
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                                <dt class="flex flex-col">
                                    <span>Tax estimate</span>
                                </dt>
                                <dd class="text-gray-900 font-medium">Included in price</dd>
                            </div>
                            <div class="flex items-center justify-between py-4">
                                <dt class="text-lg font-bold text-gray-900">Order Total</dt>
                                <dd class="text-2xl font-extrabold text-indigo-600">£{{ number_format($total, 2) }}</dd>
                            </div>
                        </dl>

                        <div class="mt-8 space-y-4">
                            <a href="{{ route('checkout.index') }}" class="w-full bg-green-600 border border-transparent rounded-xl shadow-md py-4 px-4 text-base font-bold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-500 transition-all duration-200 flex justify-center items-center">
                                Proceed to Checkout
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <a href="{{ route('orders.index') }}" class="w-full bg-white border border-gray-300 rounded-xl shadow-sm py-4 px-4 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 transition-all duration-200 flex justify-center text-center">
                                View Your Past Orders
                            </a>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
</x-app-layout>
