@extends('layouts.app')

@section('content')
<section style="padding: 2rem;">

    <h1>Update Inventory</h1>

    <p><strong>Product:</strong> {{ $inventory->product->name }}</p>

    <form method="POST" action="{{ route('inventory.update', $inventory->id) }}">
        @csrf
        @method('PUT')

        <label>New Quantity</label>
        <input type="number" name="quantity" value="{{ $inventory->quantity }}">

        <button type="submit">Save</button>
    </form>

</section>
@endsection
