@extends('layouts.app')
@section('title', 'Devoluciones')
@section('content')
<div class="container">
  <div class="d-flex justify-content-between mb-3"><h2>Devoluciones</h2>
    <a href="{{ route('devolucion.create') }}" class="btn btn-primary">Nueva devolución</a></div>
  <table class="table table-striped align-middle">
    <thead><tr><th>N° factura</th><th>Fecha</th><th class="text-end">Acción</th></tr></thead>
    @forelse($devoluciones as $d)
      <tr>
        <td>{{ $d->cod_detallefactura }}</td>
        <td>{{ $d->fecha }}</td>
        <td class="text-end"><a href="{{ route('devolucion.show', $d->cod_detallefactura) }}" class="btn btn-sm btn-outline-primary">Ver detalle</a></td>
      </tr>
    @empty <tr><td colspan="3" class="text-center">Sin devoluciones</td></tr> @endforelse
  </table>
</div>
@endsection