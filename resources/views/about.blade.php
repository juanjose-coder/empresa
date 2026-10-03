@extends('layouts.app')
@section('title', 'Acerca de')
@section('content')
<div class="container">
  <h1>Acerca del proyecto</h1>
  <p>Proyecto Aplicativo Integrador: sistema de facturación y control de inventarios de un almacén.
     Construido con Laravel, Blade, Bootstrap, Vite y MySQL (10 tablas con integridad referencial).</p>
  <ul>
    <li>Registro y búsqueda de clientes</li>
    <li>Registro y listado de artículos y proveedores</li>
    <li>Actualización de stock, devoluciones y facturación</li>
    <li>Autenticación con login y registro de usuarios</li>
  </ul>
</div>
@endsection
