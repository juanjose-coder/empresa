@extends('layouts.app')
@section('title', 'Detalle de devolución')
@section('content')
<div class="container" style="max-width:820px">
  <div class="d-flex justify-content-between mb-3">
    <h2>Devolución · {{ $factura->num_factura }}</h2>
    <a href="{{ route('devolucion.index') }}" class="btn btn-secondary">Volver</a>
  </div>
  <p class="text-muted">
    Cliente: {{ $factura->cliente->nombres ?? '' }} {{ $factura->cliente->apellidos ?? '' }} ·
    Fecha de la factura: {{ $factura->fecha_facturacion }} ·
    <a href="{{ route('factura.show', $factura->num_factura) }}">Ver factura</a>
  </p>
  <table class="table table-striped">
    <thead><tr><th>Artículo</th><th>Motivo</th><th>Fecha</th><th class="text-end">Cantidad</th></tr></thead>
    @foreach($items as $d)
      <tr>
        <td>{{ $arts[$d->cod_detallearticulo] ?? $d->cod_detallearticulo }}</td>
        <td>{{ $d->motivo }}</td>
        <td>{{ $d->fecha_devolucion }}</td>
        <td class="text-end">{{ $d->cantidad }}</td>
      </tr>
    @endforeach
  </table>
</div>
@endsection