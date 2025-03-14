<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>চালান</h1>
        <p>চালান নং: {{ $invoice->invoice_number }}</p>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td width="50%">
                    <strong>গ্রাহক:</strong><br>
                    {{ $invoice->customer->name }}<br>
                    {{ $invoice->customer->address }}
                </td>
                <td width="50%" style="text-align: right">
                    <strong>তারিখ:</strong> {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}<br>
                    <strong>স্ট্যাটাস:</strong>
                    {{ $invoice->status === 'paid' ? 'পরিশোধিত' : 'বাকি' }}
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>নাম</th>
                <th>পরিমাণ</th>
                <th>মূল্য</th>
                <th>ডিসকাউন্ট</th>
                <th>সর্বমোট</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }} ৳</td>
                <td>
                    {{ $item->discount_value }}
                    {{ $item->discount_type === 'percentage' ? '%' : '৳' }}
                </td>
                <td>{{ number_format($item->final_price, 2) }} ৳</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <h3>সর্বমোট: {{ number_format($invoice->items->sum('final_price'), 2) }} ৳</h3>
    </div>

    @if($invoice->note)
    <div class="note-section">
        <strong>নোট:</strong><br>
        {{ $invoice->note }}
    </div>
    @endif
</body>
</html>