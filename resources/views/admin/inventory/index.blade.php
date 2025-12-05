@extends('layouts.app')

@section('content')
<section style="padding: 2rem;">

    <h1>Inventory</h1>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($inventory as $row)
                <tr>
                    <td>{{ $row->product->name }}</td>
                    <td>{{ $row->quantity }}</td>
                    <td>
                        <a href="{{ route('inventory.edit', $row->id) }}">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No inventory yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</section>
@endsection
