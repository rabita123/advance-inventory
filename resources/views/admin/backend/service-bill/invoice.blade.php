<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
    @font-face {
        font-family: 'solaimanlipi';
        src: url('{{ storage_path('fonts/SolaimanLipi.ttf') }}') format('truetype');
    }
    body {
        font-family: 'solaimanlipi', sans-serif;
    }
</style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Your Company Name</h2>
        <p style="margin: 0;">123 Street, City, Country</p>
        <p style="margin: 0;">Phone: +880-XXX-XXXXXXX | Email: info@yourcompany.com</p>
        <hr style="margin-top: 10px;">
    </div>
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td><strong>Invoice No:</strong> #{{ $bill->id }}</td>
            <td style="text-align: right;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td><strong>Customer Name:</strong> {{ $bill->customer_name }}</td>
        </tr>
        <tr>
            <td><strong>Mobile:</strong> {{ $bill->mobile }}</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="8">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bill->items as $item)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="center">{{ $item->name }}</td>
                    <td align="center">{{ $item->qty }}</td>
                    <td align="center">{{ number_format($item->price, 2) }}</td>
                    <td align="center">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="width: 40%; float: right; margin-top: 20px;" cellpadding="5">
        <tr>
            <td><strong>Subtotal:</strong></td>
            <td>{{ number_format($bill->items->sum('total'), 2) }}</td>
        </tr>
        <tr>
            <td><strong>Discount ({{ $bill->discount }}%):</strong></td>
            <td>- {{ number_format($bill->items->sum('total') * $bill->discount / 100, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Grand Total:</strong></td>
            <td><strong>{{ number_format($bill->subtotal, 2) }}</strong></td>
        </tr>
    </table>
    <br><br>
    <div style="margin-top: 100px; text-align: left; float:left;">
        <small>I hereby acknowledge and consent to the services listed above.</small><br><br>
        {{ $bill->signature ?? '____________________' }}<br>
        <small>Signature(Customer)</small>
    </div>
    <div style="margin-top: 100px; text-align: right;">
        <p>{{ $bill->signature ?? '____________________' }}</p>
        <p><strong>Signature</strong></p>
    </div>
    <div style="text-align: center; margin-top: 50px; font-size: 12px;">
        <hr>
        <p>This is a system-generated invoice. No signature required if downloaded digitally.</p>
    </div>
</body>
</html>
