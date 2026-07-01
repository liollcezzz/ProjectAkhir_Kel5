<!DOCTYPE html><html><head><meta charset="utf-8"><title>Sales Report</title>
<style>
body{font-family:DejaVu Sans, sans-serif;font-size:12px;color:#111}
h1{margin:0 0 4px 0}
table{width:100%;border-collapse:collapse;margin-top:12px}
th,td{border:1px solid #ccc;padding:6px;text-align:left}
th{background:#f4f4f5;text-transform:uppercase;font-size:10px}
.total{text-align:right;font-weight:bold;font-size:14px;margin-top:12px}
</style></head><body>
<h1>AKSESORIA — Sales Report</h1>
<p>Period: {{ \Illuminate\Support\Carbon::parse($from)->format('d M Y') }} → {{ \Illuminate\Support\Carbon::parse($to)->format('d M Y') }}<br>
Cashier: {{ auth()->user()->name }}</p>
<table><thead><tr><th>Code</th><th>Date</th><th>Items</th><th>Total</th></tr></thead><tbody>
@foreach($orders as $o)
<tr><td>{{ $o->code }}</td><td>{{ $o->created_at->format('d M Y H:i') }}</td>
    <td>{{ $o->items->sum('quantity') }}</td><td>Rp {{ number_format($o->total,0,',','.') }}</td></tr>
@endforeach
</tbody></table>
<p class="total">TOTAL: Rp {{ number_format($total,0,',','.') }}</p>
</body></html>
