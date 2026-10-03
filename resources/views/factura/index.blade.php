@extends('layouts.app')
@section('title', 'Facturas')
@section('content')
<div class="container">
  <div class="d-flex justify-content-between mb-3"><h2>Facturas</h2>
    <a href="{{ route('factura.create') }}" class="btn btn-primary">Nueva factura</a></div>
  <table class="table table-striped">
    <thead><tr><th>N°</th><th>Fecha</th><th>Cliente</th><th>Empleado</th><th>IGV</th><th>Total</th><th></th></tr></thead>
    @forelse($facturas as $f)
      <tr><td>{{ $f->num_factura }}</td><td>{{ $f->fecha_facturacion }}</td>
          <td>{{ $f->cliente->nombres ?? '' }} {{ $f->cliente->apellidos ?? '' }}</td>
          <td>{{ $f->nombre_empleado }}</td><td>{{ number_format($f->igv,2) }}</td>
          <td>{{ number_format($f->total_factura,2) }}</td>
          <td><a class="btn btn-sm btn-info" href="{{ route('factura.show',$f->num_factura) }}">Ver</a></td></tr>
    @empty <tr><td colspan="7" class="text-center">Sin facturas</td></tr> @endforelse
  </table>
</div>
@endsection