@extends('layouts.app')
@section('title', 'Factura')
@section('content')
@php
  [$serie, $correlativo] = explode('-', $factura->num_factura);
  $subtotal = $factura->total_factura - $factura->igv;
  $tipoCliente = strtoupper($tipoDoc ?? '') === 'RUC' ? '6' : '1';   // catálogo SUNAT: 6 = RUC, 1 = DNI

  // Texto del QR según el formato de SUNAT: RUC | tipo | serie | correlativo | IGV | total | fecha | tipo doc. cliente | N° doc. cliente |
  $qrTexto = implode('|', [
      $empresa->ruc ?? '', '01', $serie, $correlativo,
      number_format($factura->igv, 2, '.', ''), number_format($factura->total_factura, 2, '.', ''),
      $factura->fecha_facturacion, $tipoCliente, $factura->cod_cliente, '',
  ]);
  $hash = base64_encode(sha1($qrTexto, true));
@endphp

<style>
  .factura{background:#fff;max-width:860px;margin:0 auto;padding:2rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.9rem}
  .factura .caja{border:2px solid #111827;border-radius:.4rem;padding:.8rem 1.2rem;text-align:center;min-width:250px}
  .factura .datos th{width:150px;white-space:nowrap;background:none;font-weight:600}
  .factura .datos td,.factura .datos th{padding:.15rem .4rem;border:0}
  .factura .detalle th{background:#f3f4f6}
  .factura .letras{font-weight:600;margin:.8rem 0}
  .factura .pie{border-top:1px solid #d1d5db;margin-top:1.5rem;padding-top:1rem}
  @media print{
    .sidebar,.topbar,.footer,.no-print{display:none !important}
    .main-wrap{margin-left:0 !important}
    body{background:#fff !important}
    .factura{border:0;padding:0;max-width:100%}
    @page{margin:1.2cm}
  }
</style>

<div class="container">
  <div class="no-print d-flex justify-content-between mb-3">
    <a href="{{ route('factura.index') }}" class="btn btn-secondary">Volver</a>
    <div class="d-flex gap-2">
      <a href="{{ route('devolucion.create', ['factura' => $factura->num_factura]) }}" class="btn btn-outline-primary"><i class="bi bi-arrow-return-left"></i> Devolver artículos</a>
      <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Imprimir</button>
    </div>
  </div>

  <div class="factura">
    @if(!$empresa)
      <div class="alert alert-warning no-print">Aún no registraste los datos de la empresa. <a href="{{ route('empresa') }}">Registrarlos</a></div>
    @endif

    {{-- Cabecera: emisor y recuadro con RUC, tipo de comprobante y serie-correlativo --}}
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
      <div class="d-flex align-items-center gap-3">
        @if($empresa && $empresa->logo)<img src="{{ asset('img/'.$empresa->logo) }}" alt="Logo" style="max-height:80px">@endif
        <div>
          <h5 class="mb-0">{{ $empresa->razon_social ?? '' }}</h5>
          <small class="text-muted">
            {{ $empresa->nombre ?? '' }}<br>
            Domicilio fiscal: {{ $empresa->direccion ?? '' }}<br>
            Tel. {{ $empresa->telefono ?? '' }} · {{ $empresa->correo ?? '' }}
          </small>
        </div>
      </div>
      <div class="caja">
        <div class="fw-semibold">R.U.C. {{ $empresa->ruc ?? '—' }}</div>
        <div class="fw-bold fs-6 my-1">FACTURA ELECTRÓNICA</div>
        <div class="fw-semibold">{{ $factura->num_factura }}</div>
      </div>
    </div>

    {{-- Datos de la factura y del adquirente --}}
    <table class="table table-sm datos mb-3">
      <tr><th>Fecha de emisión</th><td>{{ $factura->fecha_facturacion }}</td></tr>
      <tr><th>Señor(es)</th><td>{{ $factura->cliente->nombres ?? '' }} {{ $factura->cliente->apellidos ?? '' }}</td></tr>
      <tr><th>{{ $tipoDoc ?? 'Documento' }}</th><td>{{ $factura->cod_cliente }}</td></tr>
      <tr><th>Dirección</th><td>{{ $factura->cliente->direccion ?? '' }}</td></tr>
      <tr><th>Tipo de moneda</th><td>SOLES (PEN)</td></tr>
      <tr><th>Forma de pago</th><td>{{ $pago ?? '' }}</td></tr>
      <tr><th>Atendido por</th><td>{{ $factura->nombre_empleado }}</td></tr>
    </table>

    {{-- Detalle --}}
    <table class="table table-bordered table-sm align-middle detalle">
      <thead>
        <tr><th>Cant.</th><th>Unidad</th><th>Descripción</th><th class="text-end">Valor unit.</th><th class="text-end">Valor venta</th></tr>
      </thead>
      <tbody>
      @foreach($factura->detalles as $d)
        <tr>
          <td>{{ $d->cantidad }}</td>
          <td>UNIDAD</td>
          <td>{{ $arts[$d->cod_articulo] ?? $d->cod_articulo }}</td>
          <td class="text-end">{{ number_format($d->total / $d->cantidad, 2) }}</td>
          <td class="text-end">{{ number_format($d->total, 2) }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <div class="letras">{{ \App\Support\NumeroALetras::soles($factura->total_factura) }}</div>

    <div class="d-flex justify-content-end">
      <table class="table table-sm table-bordered w-auto mb-0">
        <tr><th class="text-end">Op. gravadas (S/)</th><td class="text-end" style="min-width:110px">{{ number_format($subtotal, 2) }}</td></tr>
        <tr><th class="text-end">Op. exoneradas (S/)</th><td class="text-end">0.00</td></tr>
        <tr><th class="text-end">Op. inafectas (S/)</th><td class="text-end">0.00</td></tr>
        <tr><th class="text-end">IGV 18% (S/)</th><td class="text-end">{{ number_format($factura->igv, 2) }}</td></tr>
        <tr><th class="text-end">Importe total (S/)</th><td class="text-end fw-bold">{{ number_format($factura->total_factura, 2) }}</td></tr>
      </table>
    </div>

    {{-- Pie: QR, hash y leyendas --}}
    <div class="pie d-flex gap-3 align-items-center">
      <div>{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(110)->margin(0)->generate($qrTexto) !!}</div>
      <div class="small">
        <div><b>Hash:</b> {{ $hash }}</div>
        <div>Representación impresa de la Factura Electrónica.</div>
        <div class="text-muted">Documento de uso académico: no fue enviado a SUNAT (sin XML firmado ni CDR), por lo que no tiene validez tributaria.</div>
      </div>
    </div>
  </div>
</div>
@endsection
