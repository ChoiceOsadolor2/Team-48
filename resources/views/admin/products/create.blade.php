@extends('layouts.app')

@section('content')
    <section style="padding: 2rem;">
        <h1>Products</h1>
        <p>Add a new product to the system.</p>

        <p>
            <a href="{{ route('products.index') }}">← Back to products</a>
        </p>

        {{-- Show validation errors --}}
        @if ($errors->any())
            <div style="border: 1px solid red; padding: 1rem; margin-bottom: 1rem;">
                <strong>There were some problems with your input:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" style="max-width: 400px;">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="name">Name</label><br>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    style="width: 100%;"
                >
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="category_id">Category</label><br>
                <select id="category_id" name="category_id" required style="width: 100%;">
                    <option value="">-- Select a category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="price">Price (£)</label><br>
                <input
                    type="number"
                    step="0.01"
                    id="price"
                    name="price"
                    value="{{ old('price') }}"
                    required
                    style="width: 100%;"
                >
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="stock">Stock</label><br>
                <input
                    type="number"
                    id="stock"
                    name="stock"
                    value="{{ old('stock') }}"
                    required
                    style="width: 100%;"
                >
            </div>

            <button type="submit">Save Product</button>
        </form>
    </section>
@endsection
