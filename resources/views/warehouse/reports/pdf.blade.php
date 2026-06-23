<!DOCTYPE html><html><head><meta charset="utf-8"><title>Inventory Report</title>
<style>body{font-family:DejaVu Sans, sans-serif;font-size:12px;color:#111}
table{width:100%;border-collapse:collapse;margin-top:12px}
th,td{border:1px solid #ccc;padding:6px;text-align:left}
th{background:#f4f4f5;text-transform:uppercase;font-size:10px}
.low{color:#b45309}.out{color:#b91c1c;font-weight:bold}</style></head><body>
<h1>AKSESORIA — Current Inventory</h1>
<p>Generated {{ now()->format('d M Y H:i') }}</p>
<table><thead><tr><th>SKU</th><th>Name</th><th>Gender</th><th>Categories</th><th>Price</th><th>Stock</th></tr></thead><tbody>
@foreach($products as $p)
<tr>
  <td>{{ $p->sku }}</td><td>{{ $p->name }}</td><td>{{ $p->gender }}</td>
  <td>{{ $p->categories->pluck('name')->join(', ') }}</td>
  <td>Rp {{ number_format($p->price,0,',','.') }}</td>
  <td class="{{ $p->stock===0 ? 'out' : ($p->stock<5?'low':'') }}">{{ $p->stock }}</td>
</tr>
@endforeach
</tbody></table>
</body></html>
