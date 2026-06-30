<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice {{ $order->code }}</title>
<style>
@page { margin: 32px; }
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; }
.header { text-align: center; margin-bottom: 24px; }
.header h1 { font-size: 22px; letter-spacing: 6px; color: #c9a23e; margin: 0; }
.header p { font-size: 10px; color: #888; margin: 2px 0 0 0; }
.meta { width: 100%; margin-bottom: 20px; }
.meta td { vertical-align: top; padding: 2px 0; }
.meta .label { font-size: 9px; text-transform: uppercase; color: #888; }
.meta .value { font-size: 11px; font-weight: 600; }
table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
table.items th { background: #f4f4f5; text-transform: uppercase; font-size: 9px; padding: 8px; text-align: left; border-bottom: 2px solid #e4e4e7; }
table.items td { padding: 7px 8px; border-bottom: 1px solid #e4e4e7; }
table.totals { width: 100%; }
table.totals td { padding: 4px 8px; }
table.totals tr:last-child td { border-top: 2px solid #1a1a1a; font-weight: 700; font-size: 13px; }
.status-paid { color: #16a34a; font-weight: 700; }
.status-unpaid { color: #dc2626; font-weight: 700; }
.badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 10px; }
.badge-paid { background: #dcfce7; color: #16a34a; }
.badge-unpaid { background: #fef2f2; color: #dc2626; }
.footer { position: fixed; bottom: 32px; left: 32px; right: 32px; text-align: center; font-size: 9px; color: #aaa; border-top: 1px solid #e4e4e7; padding-top: 8px; }
.watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 72px; opacity: 0.06; letter-spacing: 12px; color: #16a34a; }
</style>
</head>
<body>

@if($order->payment_status === 'paid')
  <div class="watermark">PAID</div>
@endif

<div class="header">
  <h1>AKSESORIA</h1>
  <p>Fashion Accessories — Invoice</p>
</div>

<table class="meta">
  <tr>
    <td style="width: 50%;">
      <div class="label">Order Code</div>
      <div class="value">{{ $order->code }}</div>
      <div class="label" style="margin-top:8px;">Date</div>
      <div class="value">{{ $order->created_at->format('d M Y H:i') }}</div>
    </td>
    <td style="width: 50%; text-align: right;">
      <div class="label">Customer</div>
      <div class="value">{{ $order->customer_name }}</div>
      <div class="value">{{ $order->customer_phone }}</div>
    </td>
  </tr>
</table>

<table class="items">
  <thead>
    <tr><th style="width:50%;">Item</th><th style="width:15%;">Price</th><th style="width:10%;">Qty</th><th style="width:25%; text-align:right;">Total</th></tr>
  </thead>
  <tbody>
    @foreach($order->items as $i)
    <tr>
      <td>{{ $i->product_name }}<br><span style="font-size:9px;color:#888;">SKU: {{ $i->product_sku }}</span></td>
      <td>Rp {{ number_format($i->unit_price,0,',','.') }}</td>
      <td>{{ $i->quantity }}</td>
      <td style="text-align:right;">Rp {{ number_format($i->line_total,0,',','.') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<table class="totals">
  <tr><td style="width:50%;"><strong>Shipping:</strong> {{ $sm['label'] ?? $order->shipping_method ?? '-' }}</td>
      <td style="text-align:right;">Rp {{ number_format($order->shipping_cost,0,',','.') }}</td></tr>
  <tr><td><strong>Subtotal</strong></td>
      <td style="text-align:right;">Rp {{ number_format($order->subtotal,0,',','.') }}</td></tr>
  <tr><td><strong>Total</strong></td>
      <td style="text-align:right;">Rp {{ number_format($order->total,0,',','.') }}</td></tr>
</table>

<div style="margin-top:16px;">
  <strong>Payment:</strong>
  <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
    {{ $order->payment_status === 'paid' ? 'PAID' : 'UNPAID' }}
  </span>
  @if($order->payment_method)
    <span style="font-size:10px;color:#888;margin-left:8px;">
      {{ str_replace('_',' ',ucfirst($order->payment_method)) }}
    </span>
  @endif
</div>

@if($order->notes)
  <div style="margin-top:12px;padding:8px;background:#f4f4f5;font-size:10px;">
    <strong>Notes:</strong> {{ $order->notes }}
  </div>
@endif

<div class="footer">
  AKSESORIA — {{ date('Y') }} · Thank you for your purchase!
</div>

</body>
</html>
