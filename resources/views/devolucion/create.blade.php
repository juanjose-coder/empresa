@extends('layouts.app')
@section('title', 'Nueva devolución')
@section('content')
<div class="container" style="max-width:820px">
  <h2>Registrar devolución</h2>

  <form method="GET" action="{{ route('devolucion.create') }}" class="card p-3 mb-3">
    <label class="form-label fw-semibold">1. Selecciona la factura</label>
    <div class="input-group">
      <select name="factura" class="form-select" onchange="this.form.submit()">
        <option value="">-- Selecciona una factura --</option>
        @foreach($facturas as $f)
          <option value="{{ $f->num_factura }}" @selected($factura && $factura->num_factura === $f->num_factura)>
            {{ $f->num_factura }} · {{ $f->cliente->nombres ?? '' }} {{ $f->cliente->apellidos ?? '' }} · {{ $f->fecha_facturacion }} · S/ {{ number_format($f->total_factura, 2) }}
          </option>
        @endforeach
      </select>
      <button class="btn btn-outline-primary">Ver artículos</button>
    </div>
  </form>

  @if($factura && $yaDevuelta)
    <div class="alert alert-warning">Esta factura ya tuvo devoluciones. Elija otra.</div>
    <a href="{{ route('devolucion.index') }}" class="btn btn-secondary">Cancelar</a>

  @elseif($factura)
  <form method="POST" action="{{ route('devolucion.store') }}" class="card p-3">
    @csrf
    <input type="hidden" name="cod_detallefactura" value="{{ $factura->num_factura }}">

    <p class="text-muted mb-3">
      Factura <b>{{ $factura->num_factura }}</b> · Cliente: {{ $factura->cliente->nombres ?? '' }} {{ $factura->cliente->apellidos ?? '' }} · Fecha: {{ $factura->fecha_facturacion }}
    </p>

    <label class="form-label fw-semibold">2. Indica cuántas unidades se devuelven de cada artículo</label>
    <table class="table align-middle mb-3">
      <thead><tr><th>Artículo</th><th class="text-center">Vendido</th><th style="width:190px">Cantidad a devolver</th></tr></thead>
      <tbody>
      @foreach($lineas as $l)
        <tr>
          <td>{{ $l->descripcion }}</td>
          <td class="text-center">{{ $l->vendido }}</td>
          <td><input type="number" name="items[{{ $l->id }}]" min="0" max="{{ $l->vendido }}"
                     value="{{ old('items.'.$l->id, 0) }}" class="form-control"></td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <label class="form-label fw-semibold">3. Motivo</label>
    <select name="motivo" class="form-select mb-3" required>
      <option value="">-- Selecciona un motivo --</option>
      @foreach($motivos as $m)
        <option value="{{ $m }}" @selected(old('motivo') === $m)>{{ $m }}</option>
      @endforeach
    </select>

    <div>
      <button class="btn btn-primary">Registrar devolución</button>
      <a href="{{ route('devolucion.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>

  @else
    <a href="{{ route('devolucion.index') }}" class="btn btn-secondary">Cancelar</a>
  @endif
</div>
@endsection