@extends('layouts.dashboard')
@section('title','POS')
@section('heading','Point of Sale')
@section('content')
<div class="grid lg:grid-cols-3 gap-6" id="pos" x-data>
  <div class="lg:col-span-2">
    <form method="GET" class="mb-4">
      <input name="q" value="{{ $q }}" class="input" placeholder="Search product by name or SKU…">
    </form>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
      @forelse($products as $p)
        <button type="button"
          data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock }}"
          class="card p-3 text-left hover:border-ink-900 transition add-product">
          <div class="aspect-square bg-ink-100 rounded mb-2 overflow-hidden">
            <img src="{{ $p->imageUrl() }}" class="w-full h-full object-cover">
          </div>
          <p class="text-xs text-ink-500 font-mono">{{ $p->sku }}</p>
          <p class="text-sm font-medium leading-tight">{{ $p->name }}</p>
          <p class="text-sm mt-1">Rp {{ number_format($p->price,0,',','.') }}</p>
          <p class="text-xs text-ink-500">Stock: {{ $p->stock }}</p>
        </button>
      @empty
        <p class="col-span-3 text-ink-500">No matching products.</p>
      @endforelse
    </div>
  </div>

  <aside class="card p-4 sticky top-6 h-fit">
    <h2 class="font-semibold mb-3">Cart</h2>
    <form method="POST" action="{{ route('cashier.pos.store') }}" id="pos-form">
      @csrf
      <div id="cart-rows" class="space-y-2 text-sm max-h-96 overflow-y-auto"></div>
      <p id="cart-empty" class="text-ink-500 text-sm">No items.</p>
      <div class="border-t border-ink-200 mt-4 pt-3 space-y-2 text-sm">
        <div class="flex justify-between"><span>Total</span><span id="total" class="font-semibold">Rp 0</span></div>
        <div>
          <label class="text-xs uppercase text-ink-500">Customer name (optional)</label>
          <input name="customer_name" class="input mt-1">
        </div>
        <div>
          <label class="text-xs uppercase text-ink-500">Phone (optional)</label>
          <input name="customer_phone" class="input mt-1">
        </div>
        <div>
          <label class="text-xs uppercase text-ink-500">Amount paid</label>
          <input name="amount_paid" id="amount_paid" type="number" step="0.01" min="0" class="input mt-1" oninput="updateChange()">
        </div>
        <div class="flex justify-between"><span>Change</span><span id="change" class="font-semibold">Rp 0</span></div>
        <button class="btn-dark w-full mt-2" id="pay-btn" disabled>Charge</button>
      </div>
    </form>
  </aside>
</div>

<script>
  const cart = new Map(); // id -> {name, price, qty, stock}
  function fmt(n){ return 'Rp ' + Math.round(n).toLocaleString('id-ID'); }
  function addItem(id, name, price, stock){
    const row = cart.get(id);
    const qty = row ? Math.min(row.qty + 1, stock) : 1;
    cart.set(id, { name, price, qty, stock });
    render();
  }
  document.querySelectorAll('.add-product').forEach(btn => {
    btn.addEventListener('click', () => {
      addItem(+btn.dataset.id, btn.dataset.name, +btn.dataset.price, +btn.dataset.stock);
    });
  });
  function setQty(id, qty){
    const row = cart.get(id); if(!row) return;
    qty = Math.max(0, Math.min(parseInt(qty)||0, row.stock));
    if(qty===0) cart.delete(id); else { row.qty = qty; cart.set(id, row); }
    render();
  }
  function render(){
    const el = document.getElementById('cart-rows');
    el.innerHTML = '';
    let total = 0;
    cart.forEach((r, id) => {
      total += r.price * r.qty;
      el.insertAdjacentHTML('beforeend', `
        <div class="flex items-center justify-between gap-2 border-b border-ink-100 pb-2">
          <div class="flex-1">
            <p class="font-medium leading-tight">${r.name}</p>
            <p class="text-xs text-ink-500">${fmt(r.price)} × <input type="number" min="0" max="${r.stock}" value="${r.qty}" class="input w-16 inline" onchange="setQty(${id}, this.value)"></p>
          </div>
          <p class="font-medium">${fmt(r.price*r.qty)}</p>
          <input type="hidden" name="items[${id}][id]" value="${id}">
          <input type="hidden" name="items[${id}][qty]" value="${r.qty}">
        </div>`);
    });
    document.getElementById('cart-empty').style.display = cart.size ? 'none' : 'block';
    document.getElementById('total').textContent = fmt(total);
    document.getElementById('pay-btn').disabled = cart.size === 0;
    updateChange();
  }
  function updateChange(){
    let total = 0; cart.forEach(r => total += r.price*r.qty);
    const paid = parseFloat(document.getElementById('amount_paid').value || 0);
    document.getElementById('change').textContent = fmt(Math.max(0, paid - total));
  }
</script>
@endsection