@extends('layouts.app')
@section('title', 'Empresa')
@section('content')
<div class="container" style="max-width:640px">
  <h2>Datos de la empresa</h2>
  <form method="POST" action="{{ route('empresa.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
     <div class="mb-3"><label class="form-label">Nombre de la empresa</label>
     <input name="nombre" maxlength="60" class="form-control" value="{{ old('nombre', $empresa->nombre) }}" required></div>
    <div class="mb-3"><label class="form-label">RUC</label>
      <input name="ruc" maxlength="11" class="form-control" value="{{ old('ruc', $empresa->ruc) }}" required></div>
    <div class="mb-3"><label class="form-label">Razón social</label>
      <input name="razon_social" class="form-control" value="{{ old('razon_social', $empresa->razon_social) }}" required></div>
    <div class="mb-3"><label class="form-label">Dirección</label>
      <input name="direccion" class="form-control" value="{{ old('direccion', $empresa->direccion) }}"></div>
    <div class="row">
      <div class="col-md-6 mb-3"><label class="form-label">Teléfono</label>
        <input name="telefono" class="form-control" value="{{ old('telefono', $empresa->telefono) }}"></div>
      <div class="col-md-6 mb-3"><label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control" value="{{ old('correo', $empresa->correo) }}"></div>
    </div>
    <div class="mb-3"><label class="form-label">Logo</label>
      @if($empresa->logo)
        <div class="mb-2"><img src="{{ asset('img/'.$empresa->logo) }}?v={{ time() }}" alt="Logo" style="max-height:80px"></div>
      @endif
      <input type="file" name="logo" accept="image/*" class="form-control"></div>
    <button class="btn btn-primary">Guardar</button>
  </form>
</div>
@endsection