@extends('layouts.app')
@section('title', 'Inicio')
@section('content')
<div class="container">
@guest
  <div class="auth-wrap">
    <div class="auth-logo-plain"><img src="{{ asset('img/Laravel.svg') }}" alt="Logo"></div>
    <div class="auth-card auth-lg text-center">
      <h1 class="auth-title">Sistema de Facturación y Control de Inventarios</h1>
      <p class="auth-text">Aplicación web desarrollada con Laravel y MySQL que permite registrar clientes,
        artículos y proveedores, actualizar el stock, registrar devoluciones y generar facturas con
        cálculo automático de totales e impuestos (IGV).</p>
      <div class="auth-actions center">
        <a href="{{ route('login') }}" class="btn-auth">Ingresar</a>
        <a href="{{ route('register') }}" class="btn-auth-outline">Registrarse</a>
      </div>
    </div>
  </div>
@else
  <h2 class="mb-1">Panel de control</h2>
  <p class="text-muted">Resumen general del sistema de facturación e inventarios.</p>

  <div class="row g-3 mb-4">
    @foreach([
      ['Clientes',$stats['clientes'],'bi-people','#2563eb'],
      ['Artículos',$stats['articulos'],'bi-box-seam','#0369a1'],
      ['Proveedores',$stats['proveedores'],'bi-truck','#f59e0b'],
      ['Facturas',$stats['facturas'],'bi-receipt','#10b981'],
      ['Ventas (S/)',number_format($stats['ventas'],2),'bi-cash-coin','#0891b2'],
      ['Stock bajo',$stats['bajo'],'bi-exclamation-triangle','#ef4444'],
    ] as [$t,$v,$i,$c])
      <div class="col-md-6 col-xl-4"><div class="card stat">
        <div class="ico" style="background:{{ $c }}"><i class="bi {{ $i }}"></i></div>
        <div><h3>{{ $v }}</h3><small>{{ $t }}</small></div>
      </div></div>
    @endforeach
  </div>

  <div class="row g-3 mb-4">
    <div class="col-lg-8"><div class="card p-3"><h5>Ventas por mes (S/)</h5>
      @if($graf['meses']->isEmpty())<p class="text-muted mb-0">Sin ventas registradas todavía.</p>
      @else<div style="height:200px"><canvas id="gMeses"></canvas></div>@endif</div></div>
    <div class="col-lg-4"><div class="card p-3"><h5>Stock por tipo</h5>
      @if($graf['stock']->isEmpty())<p class="text-muted mb-0">Sin artículos todavía.</p>
      @else<div style="height:200px"><canvas id="gStock"></canvas></div>@endif</div></div>
    <div class="col-12"><div class="card p-3"><h5>Artículos más vendidos (unidades)</h5>
      @if($graf['top']->isEmpty())<p class="text-muted mb-0">Sin ventas registradas todavía.</p>
      @else<div style="height:180px"><canvas id="gTop"></canvas></div>@endif</div></div>
  </div>

  <div class="row g-3">
    <div class="col-lg-8"><div class="card p-3">
      <div class="d-flex justify-content-between mb-2"><h5 class="mb-0">Últimas facturas</h5>
        <a href="{{ route('factura.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Nueva factura</a></div>
      <table class="table mb-0"><thead><tr><th>N°</th><th>Fecha</th><th>Cliente</th><th>Total</th></tr></thead>
        @forelse($ultimas as $f)
          <tr><td>{{ $f->num_factura }}</td><td>{{ $f->fecha_facturacion }}</td><td>{{ $f->cod_cliente }}</td><td>{{ number_format($f->total_factura,2) }}</td></tr>
        @empty<tr><td colspan="4" class="text-center text-muted">Aún no hay facturas</td></tr>@endforelse
      </table></div></div>
    <div class="col-lg-4"><div class="card p-3 h-100">
      <h5>Accesos rápidos</h5>
      <div class="d-grid gap-2">
        <a class="btn btn-outline-primary" href="{{ route('cliente.create') }}"><i class="bi bi-person-plus"></i> Nuevo cliente</a>
        <a class="btn btn-outline-primary" href="{{ route('articulo.create') }}"><i class="bi bi-box"></i> Nuevo artículo</a>
        <a class="btn btn-outline-primary" href="{{ route('devolucion.create') }}"><i class="bi bi-arrow-return-left"></i> Registrar devolución</a>
      </div></div></div>
  </div>
@endguest
</div>
@endsection

@auth
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const colores = ['#2563eb','#0891b2','#10b981','#f59e0b','#ef4444','#64748b'];
const opt = {responsive:true,maintainAspectRatio:false};
if (document.getElementById('gMeses')) new Chart(gMeses, {type:'line', data:{labels:@json($graf['meses']->pluck('mes')),
  datasets:[{label:'Ventas',data:@json($graf['meses']->pluck('total')),borderColor:'#2563eb',backgroundColor:'rgba(37,99,235,.12)',fill:true,tension:.3}]},
  options:{...opt,plugins:{legend:{display:false}}}});
if (document.getElementById('gStock')) new Chart(gStock, {type:'doughnut', data:{labels:@json($graf['stock']->pluck('nombre')),
  datasets:[{data:@json($graf['stock']->pluck('total')),backgroundColor:colores}]},
  options:{...opt,plugins:{legend:{position:'bottom'}}}});
if (document.getElementById('gTop')) new Chart(gTop, {type:'bar', data:{labels:@json($graf['top']->pluck('nombre')),
  datasets:[{label:'Unidades',data:@json($graf['top']->pluck('total')),backgroundColor:'#0891b2',borderRadius:6}]},
  options:{...opt,plugins:{legend:{display:false}}}});
</script>
@endpush
@endauth
