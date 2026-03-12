<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen">
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

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-8">
                
                <a href="{{ route('admin.users.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-blue-100 dark:bg-blue-900/40 p-5 rounded-2xl mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-2xl font-bold text-gray-900 dark:text-white mb-3">Manage Users</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">View, edit, or safely remove user accounts from the platform.</p>
                </a>

                <a href="{{ route('admin.products.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/10 dark:to-teal-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-emerald-100 dark:bg-emerald-900/40 p-5 rounded-2xl mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-2xl font-bold text-gray-900 dark:text-white mb-3">Inventory</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Manage stock levels, easily add new products, and update pricing.</p>
                </a>

                <a href="{{ route('admin.products.stock') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-amber-100 dark:bg-amber-900/40 p-5 rounded-2xl mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3V4a2 2 0 10-4 0v1H8a2 2 0 00-2 2v6m14 0v5a2 2 0 01-2 2H8a2 2 0 01-2-2v-5m14 0H6"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-2xl font-bold text-gray-900 dark:text-white mb-3">Stock Status</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">See which products are available now and which ones need restocking.</p>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-purple-100 dark:bg-purple-900/40 p-5 rounded-2xl mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-10 h-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-2xl font-bold text-gray-900 dark:text-white mb-3">View Orders</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Process customer orders, view full details, and track order fulfillments.</p>
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col items-center text-center translate-y-0 hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-sky-50 dark:from-cyan-900/10 dark:to-sky-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="z-10 bg-cyan-100 dark:bg-cyan-900/40 p-5 rounded-2xl mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-inner">
                        <svg class="w-10 h-10 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M7 4h10a2 2 0 012 2v12l-4-3H7a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                    <h4 class="z-10 text-2xl font-bold text-gray-900 dark:text-white mb-3">Chatbot FAQs</h4>
                    <p class="z-10 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Add and update FAQ answers that power the smarter chatbot.</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
