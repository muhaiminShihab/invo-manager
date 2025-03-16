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
            padding: 40px;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .company-logo {
            margin-bottom: 10px;
        }
        .company-info {
            font-size: 20px;
            color: #666;
        }
        .invoice-title {
            color: #2c3e50;
            margin: 20px 0 0;
        }
        .invoice-number {
            color: #666;
            font-size: 16px;
        }
        .invoice-details {
            margin-bottom: 40px;
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
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-section {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .total-amount {
            font-size: 24px;
            color: #2c3e50;
            font-weight: bold;
        }
        .note-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="company-logo">
            <img src="{{ asset('media/logo.png') }}" width="250px" alt="Company Logo">
        </div>
        <div class="company-info">
            {{ $settings->where('key', 'name')->first()->value }}<br>
            {{ $settings->where('key', 'address')->first()->value }}<br>
            {{ $settings->where('key', 'phone')->first()->value }} | {{ $settings->where('key', 'email')->first()->value }}
        </div>
        <h1 class="invoice-title">চালান ({{ $invoice->invoice_number }})</h1>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td width="50%">
                    <div class="customer-info">
                        <strong>গ্রাহক:</strong><br>
                        <span style="font-size: 18px;">{{ $invoice->customer->name }}</span><br>
                        {{ $invoice->customer->phone }}
                    </div>
                </td>
                <td width="50%" style="text-align: right">
                    <div class="customer-info">
                        <strong>তারিখ:</strong> {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}<br>
                        <strong>স্ট্যাটাস:</strong>
                        <span style="color: {{ $invoice->status === 'paid' ? '#28a745' : '#dc3545' }}">
                            {{ $invoice->status === 'paid' ? 'পরিশোধিত' : 'বাকি' }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>নাম</th>
                <th style="width: 100px;">পরিমাণ</th>
                <th style="width: 120px;">মূল্য</th>
                <th style="width: 120px;">ডিসকাউন্ট</th>
                <th style="width: 150px;">সর্বমোট</th>
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
        <h3 class="total-amount">সর্বমোট: {{ number_format($invoice->items->sum('final_price'), 2) }} ৳</h3>
    </div>

    @if($invoice->note)
    <div class="note-section">
        <strong>নোট:</strong><br>
        {{ $invoice->note }}
    </div>
    @endif
</body>
</html>