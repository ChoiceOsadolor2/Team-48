<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- TOP NAV -->
    <nav class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Admin Panel</h1>

        <div class="flex gap-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
            <a href="{{ route('products.index') }}" class="hover:underline">Products</a>
            <a href="{{ route('categories.index') }}" class="hover:underline">Categories</a>
            <a href="{{ route('inventory.index') }}" class="hover:underline">Inventory</a>
            <a href="{{ route('inventory.logs') }}" class="hover:underline">Logs</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="hover:underline text-red-400">Log out</button>
            </form>
        </div>
    </nav>

    <main class="p-6">
        {{ $slot }}
    </main>

</body>
</html>
