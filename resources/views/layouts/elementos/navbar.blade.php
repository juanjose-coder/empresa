<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('inicio') }}">Facturación</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('inicio') }}">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Acerca de</a></li>
        @auth
          <li class="nav-item"><a class="nav-link" href="{{ route('cliente.index') }}">Clientes</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('articulo.index') }}">Artículos</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('proveedor.index') }}">Proveedores</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('factura.index') }}">Facturas</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('devolucion.index') }}">Devoluciones</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('consultas') }}">Consultas</a></li>
        @endauth
      </ul>
      <ul class="navbar-nav">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
        @else
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button class="btn btn-link nav-link">Salir ({{ Auth::user()->name }})</button>
            </form>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
