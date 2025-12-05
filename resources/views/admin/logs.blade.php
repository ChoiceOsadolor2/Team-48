<h1>Stock Logs</h1>

<table>
    <tr>
        <th>Product</th>
        <th>Old Qty</th>
        <th>New Qty</th>
        <th>Action</th>
        <th>Date</th>
    </tr>

    @foreach($logs as $log)
    <tr>
        <td>{{ $log->product->name }}</td>
        <td>{{ $log->old_quantity }}</td>
        <td>{{ $log->new_quantity }}</td>
        <td>{{ $log->action }}</td>
        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
    </tr>
    @endforeach
</table>
