<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veltrix Invoice VX-{{ $order->id }}</title>
    <style>
        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            background: #fff;
        }

        .invoice-wrap {
            max-width: 960px;
            margin: 0 auto;
            border: 2px solid #111;
            border-radius: 20px;
            padding: 32px;
            box-sizing: border-box;
        }

        .brand {
            margin: 0 0 16px;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .title {
            margin: 0 0 24px;
            font-size: 24px;
            font-weight: 700;
        }

        .summary {
            margin-bottom: 24px;
        }

        .summary p {
            margin: 0 0 12px;
            font-size: 16px;
            line-height: 1.4;
        }

        .summary strong {
            display: inline-block;
            min-width: 110px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }

        th,
        td {
            border: 1px solid #111;
            padding: 12px 14px;
            text-align: left;
            vertical-align: top;
            font-size: 15px;
        }

        th {
            background: #f3f3f3;
            font-weight: 700;
        }

        .footer {
            margin-top: 28px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <main class="invoice-wrap">
        <h1 class="brand">VELTRIX</h1>
        <h2 class="title">Invoice VX-{{ $order->id }}</h2>

        <section class="summary">
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p>
                <strong>Shipping:</strong>
                {{ $order->shipping_method ?: 'Delivery' }}
                @if ((float) $order->shipping_cost > 0)
                    ({{ number_format($order->shipping_cost, 2) }} GBP)
                @endif
            </p>
            <p><strong>Total:</strong> {{ number_format($order->total, 2) }} GBP</p>
            <p><strong>Placed:</strong> {{ optional($order->created_at)->format('M d, Y H:i') }}</p>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Platform</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ optional($item->product)->name ?? 'Product Deleted' }}</td>
                        <td>{{ $item->platform ?: (optional($item->product)->platform ?? 'Universal') }}</td>
                        <td>{{ number_format($item->price, 2) }} GBP</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="footer">Generated from your Veltrix order history.</p>
    </main>
</body>
</html>
