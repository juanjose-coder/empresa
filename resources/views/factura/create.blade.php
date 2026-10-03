@extends('layouts.app')
@section('title', 'Nueva factura')
@section('content')
<div class="container">
  <h2>Realizar venta</h2>
  <form method="POST" action="{{ route('factura.store') }}">@csrf
    <div class="row mb-3">
      <div class="col-md-6"><label class="form-label">Cliente</label>
        <select name="cod_cliente" class="form-select" required>
          <option value="">-- Seleccione --</option>
          @foreach($clientes as $c)<option value="{{ $c->documento }}">{{ $c->documento }} - {{ $c->nombres }} {{ $c->apellidos }}</option>@endforeach
        </select></div>
      <div class="col-md-6"><label class="form-label">Forma de pago</label>
        <select name="cod_formapago" class="form-select" required>
          @foreach($pagos as $p)<option value="{{ $p->id_formapago }}">{{ $p->descripcion_formapago }}</option>@endforeach
        </select></div>
    </div>
    <table class="table" id="tabla">
      <thead><tr><th>Artículo</th><th width="120">Cantidad</th><th width="120">Subtotal</th><th></th></tr></thead>
      <tbody></tbody>
    </table>
    <button type="button" class="btn btn-outline-primary mb-3" onclick="agregar()">+ Agregar artículo</button>
    <div class="text-end">
      <p>Subtotal: <b id="sub">0.00</b> · IGV (18%): <b id="igv">0.00</b> · Total: <b id="tot">0.00</b></p>
      <button class="btn btn-success">Facturar</button>
    </div>
  </form>
</div>
@endsection

@php
    $listaArts = [];
    foreach ($articulos as $a) {
        $listaArts[] = ['id' => $a->id_articulo, 'd' => $a->descripcion, 'p' => $a->precio_venta, 's' => $a->stock];
    }
@endphp

@push('scripts')
<script>
const arts = {!! json_encode($listaArts) !!};
let n = 0;
function agregar() {
  if (arts.length === 0) { alert('No hay artículos con stock. Registra artículos primero.'); return; }
  const opts = arts.map(a => `<option value="${a.id}" data-p="${a.p}">${a.d} (S/ ${a.p} · stock ${a.s})</option>`).join('');
  document.querySelector('#tabla tbody').insertAdjacentHTML('beforeend', `
   <tr><td><select name="items[${n}][id]" class="form-select" onchange="calc()">${opts}</select></td>
   <td><input type="number" name="items[${n}][cantidad]" value="1" min="1" class="form-control" oninput="calc()"></td>
   <td class="sb">0.00</td><td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove();calc()">x</button></td></tr>`);
  n++; calc();
}
function calc() {
  let sub = 0;
  document.querySelectorAll('#tabla tbody tr').forEach(tr => {
    const p = tr.querySelector('select').selectedOptions[0].dataset.p;
    const s = p * tr.querySelector('input').value;
    tr.querySelector('.sb').textContent = s.toFixed(2); sub += s;
  });
  document.getElementById('sub').textContent = sub.toFixed(2);
  document.getElementById('igv').textContent = (sub*0.18).toFixed(2);
  document.getElementById('tot').textContent = (sub*1.18).toFixed(2);
}
agregar();
</script>
@endpush