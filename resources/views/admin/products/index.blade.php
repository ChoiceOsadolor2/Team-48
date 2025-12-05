@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>

    <a href="{{ route('products.create') }}">+ Add Product</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price (£)</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>

        @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'Uncategorised' }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>

                <td>
                    <a href="{{ route('products.edit', $product->id) }}">Edit</a>

                    <form action="{{ route('products.destroy', $product->id) }}" 
                          method="POST" 
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No products yet.</td>
            </tr>
        @endforelse
    </table>
</div>
@endsection
