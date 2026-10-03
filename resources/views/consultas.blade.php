@extends('layouts.app')
@section('title', 'Consultas')
@section('content')
<div class="container">
  <h2>Consultas</h2>
  <h5 class="mt-4">Artículos con stock bajo (≤ 5)</h5>
  <table class="table"><tr><th>ID</th><th>Descripción</th><th>Stock</th></tr>
    @forelse($bajoStock as $a)<tr><td>{{ $a->id_articulo }}</td><td>{{ $a->descripcion }}</td><td>{{ $a->stock }}</td></tr>
    @empty<tr><td colspan="3">Ninguno</td></tr>@endforelse</table>
  <h5 class="mt-4">Ventas por cliente</h5>
  <table class="table"><tr><th>Cliente</th><th>Facturas</th><th>Total</th></tr>
    @forelse($ventasCliente as $v)<tr><td>{{ $v->nombres }} {{ $v->apellidos }}</td><td>{{ $v->facturas }}</td><td>{{ number_format($v->total,2) }}</td></tr>
    @empty<tr><td colspan="3">Sin ventas</td></tr>@endforelse</table>
</div>
@endsection
