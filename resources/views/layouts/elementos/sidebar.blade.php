@php
$menu = [
  ['Principal', [['inicio','Inicio','bi-speedometer2','inicio']]],
  ['Registros', [['cliente.index','Clientes','bi-people','cliente*'],['proveedor.index','Proveedores','bi-truck','proveedor*'],['articulo.index','Artículos','bi-box-seam','articulo*']]],
  ['Operaciones', [['factura.index','Facturas','bi-receipt','factura*'],['devolucion.index','Devoluciones','bi-arrow-return-left','devolucion*']]],
  ['Reportes', [['consultas','Consultas','bi-graph-up','consultas']]],
  ['Configuración', [['empresa','Empresa','bi-building','empresa*']]],
];
@endphp
<aside class="sidebar offcanvas-lg offcanvas-start" id="sidebar">
  <a class="brand" href="{{ route('inicio') }}"><i class="bi bi-shop"></i> Facturación</a>
  @foreach($menu as [$grupo, $items])
    <div class="label">{{ $grupo }}</div>
    @foreach($items as [$ruta,$texto,$icono,$patron])
      <a class="item {{ request()->routeIs($patron) ? 'active' : '' }}" href="{{ route($ruta) }}">
        <i class="bi {{ $icono }}"></i> {{ $texto }}
      </a>
    @endforeach
  @endforeach
  <div class="label">Cuenta</div>
  <a class="item {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"><i class="bi bi-info-circle"></i> Acerca de</a>
</aside>
