<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #000;
        }
        .header, .footer {
            text-align: center;
        }
        .header h2 {
            margin: 0;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 3px 0;
        }
        .right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Noverra Roleplay</h2>
        <p>San Andreas Multiplayer</p>
        <p>{{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>Order No:</td>
            <td class="right">#{{ $order->id }}</td>
        </tr>
        <tr>
            <td>Username:</td>
            <td class="right">{{ $order->username }}</td>
        </tr>
        <tr>
            <td>Character Name:</td>
            <td class="right">{{ $order->character_name }}</td>
        </tr>
        <tr>
            <td>Payment:</td>
            <td class="right">{{ $order->payment }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <td><b>Item</b></td>
                <td class="right"><b>Qty</b></td>
                <td class="right"><b>Price</b></td>
                <td class="right"><b>Total</b></td>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($order->items as $item)
                @php $lineTotal = $item->qty * $item->price; $subtotal += $lineTotal; @endphp
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($lineTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">Subtotal</td>
            <td class="right">{{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Total ({{ $order->payment }})</td>
            <td class="right">{{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        <p>Terima Kasih Telah Berbelanja</p>
        <p>---</p>
    </div>
</body>
</html>
