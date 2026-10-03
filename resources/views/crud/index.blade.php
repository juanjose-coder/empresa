@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="container">
  <div class="d-flex justify-content-between mb-3">
    <h2>{{ $title }}</h2>
    <a href="{{ route($route.'.create') }}" class="btn btn-primary">Nuevo</a>
  </div>
  <form class="input-group mb-3" method="GET">
    <input name="q" value="{{ $search }}" class="form-control" placeholder="Buscar...">
    <button class="btn btn-outline-secondary">Buscar</button>
  </form>
  <div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead><tr>@foreach($fields as $f)<th>{{ $f['label'] }}</th>@endforeach<th></th></tr></thead>
    <tbody>
    @forelse($items as $it)
      <tr>
        @foreach($fields as $f)
          <td>{{ $f['type']=='select' ? ($f['options'][$it->{$f['name']}] ?? $it->{$f['name']}) : $it->{$f['name']} }}</td>
        @endforeach
        <td class="text-nowrap">
          <a href="{{ route($route.'.show', $it->getKey()) }}" class="btn btn-sm btn-info">Ver</a>
          <a href="{{ route($route.'.edit', $it->getKey()) }}" class="btn btn-sm btn-warning">Editar</a>
          <form action="{{ route($route.'.destroy', $it->getKey()) }}" method="POST" class="d-inline"
                onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
          @if($route=='articulo')
          <form action="{{ route('articulo.stock', $it->getKey()) }}" method="POST" class="d-inline-flex">@csrf
            <input type="number" name="cantidad" min="1" class="form-control form-control-sm" style="width:70px" placeholder="+stock">
            <button class="btn btn-sm btn-success">+</button>
          </form>
          @endif
        </td>
      </tr>
    @empty
      <tr><td colspan="{{ count($fields)+1 }}" class="text-center">No hay registros</td></tr>
    @endforelse
    </tbody>
  </table></div>
</div>
@endsection
