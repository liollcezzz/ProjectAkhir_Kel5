<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
body{font-family:sans-serif;background:#f5f5f5;padding:24px;}
.container{max-width:600px;margin:0 auto;background:white;border-radius:8px;padding:32px;}
.logo{font-size:20px;font-weight:700;letter-spacing:4px;color:#c9a23e;margin-bottom:24px;}
h2{font-size:18px;margin-bottom:16px;}
table{width:100%;border-collapse:collapse;font-size:14px;}
th,td{padding:8px 12px;text-align:left;border-bottom:1px solid #eee;}
.total-row td{border-top:2px solid #333;font-weight:700;}
.footer{margin-top:24px;font-size:12px;color:#888;text-align:center;}
</style></head>
<body>
<div class="container">
  <div class="logo">AKSESORIA</div>
  <h2>Payment Received</h2>
  <p>Your payment for order <strong>{{ $order->code }}</strong> has been received.</p>
  <table>
    <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
    @foreach($order->items as $i)
    <tr><td>{{ $i->product_name }}</td><td>{{ $i->quantity }}</td><td>Rp {{ number_format($i->line_total,0,',','.') }}</td></tr>
    @endforeach
    <tr class="total-row"><td colspan="2">Total</td><td>Rp {{ number_format($order->total,0,',','.') }}</td></tr>
  </table>
  <p style="margin-top:16px;">Thank you for your purchase!</p>
  <div class="footer">© {{ date('Y') }} Aksesoria</div>
</div>
</body>
</html>
