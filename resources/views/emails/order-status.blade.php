<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
body{font-family:sans-serif;background:#f5f5f5;padding:24px;}
.container{max-width:600px;margin:0 auto;background:white;border-radius:8px;padding:32px;}
.logo{font-size:20px;font-weight:700;letter-spacing:4px;color:#c9a23e;margin-bottom:24px;}
h2{font-size:18px;margin-bottom:16px;}
table{width:100%;border-collapse:collapse;font-size:14px;}
th,td{padding:8px 12px;text-align:left;border-bottom:1px solid #eee;}
.footer{margin-top:24px;font-size:12px;color:#888;text-align:center;}
.badge{display:inline-block;padding:2px 10px;border-radius:999px;font-size:12px;background:#fef5e0;color:#c9a23e;}
</style></head>
<body>
<div class="container">
  <div class="logo">AKSESORIA</div>
  <h2>Order Update — <span class="badge">{{ $statusLabel }}</span></h2>
  <p>Order <strong>{{ $order->code }}</strong> status has been updated to <strong>{{ $statusLabel }}</strong>.</p>
  <table>
    <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
    @foreach($order->items as $i)
    <tr><td>{{ $i->product_name }}</td><td>{{ $i->quantity }}</td><td>Rp {{ number_format($i->line_total,0,',','.') }}</td></tr>
    @endforeach
  </table>
  @if($order->tracking_number)
  <p style="margin-top:16px;">Tracking Number: <strong>{{ $order->tracking_number }}</strong></p>
  @endif
  <p style="margin-top:16px;"><a href="{{ route('customer.orders.show', $order) }}" style="background:#c9a23e;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;display:inline-block;">View Order</a></p>
  <div class="footer">© {{ date('Y') }} Aksesoria</div>
</div>
</body>
</html>
