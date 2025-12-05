@extends('layouts.app')

@section('content')
<section style="padding: 2rem;">

    <h1>Edit Product</h1>

    <a href="{{ route('products.index') }}">← Back to products</a>

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-top: 1rem;">
            <label>Name</label>
            <input type="text" name="name" value="{{ $product->name }}" required>
        </div>

        <div style="margin-top: 1rem;">
            <label>Category</label>
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 1rem;">
            <label>Price (£)</label>
            <input type="number" step="0.01" name="price"
                   value="{{ $product->price }}" required>
        </div>

        <div style="margin-top: 1rem;">
            <label>Stock</label>
            <input type="number" name="stock"
                   value="{{ $product->stock }}" required>
        </div>

        <button type="submit" style="margin-top: 1rem;">
            Update Product
        </button>
    </form>

</section>
@endsection
