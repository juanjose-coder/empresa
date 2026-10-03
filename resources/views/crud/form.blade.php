@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="container" style="max-width:640px">
  <h2>{{ $item ? 'Editar' : 'Nuevo' }} · {{ $title }}</h2>
  <form method="POST" action="{{ $item ? route($route.'.update', $item->getKey()) : route($route.'.store') }}">
    @csrf
    @if($item) @method('PUT') @endif
    @foreach($fields as $f)
      @php $ro = $item && !$item->incrementing && $f['name']==$item->getKeyName(); @endphp
      <div class="mb-3">
        <label class="form-label" for="{{ $f['name'] }}">{{ $f['label'] }}</label>
        @if($f['type']=='select')
          <select class="form-select" name="{{ $f['name'] }}" id="{{ $f['name'] }}">
            <option value="">-- Seleccione --</option>
            @foreach($f['options'] as $k=>$v)
              <option value="{{ $k }}" @selected(old($f['name'], $item->{$f['name']} ?? '') == $k)>{{ $v }}</option>
            @endforeach
          </select>
        @else
          <input type="{{ $f['type'] }}" class="form-control" id="{{ $f['name'] }}" name="{{ $f['name'] }}"
                 value="{{ old($f['name'], $item->{$f['name']} ?? '') }}" @readonly($ro)>
        @endif
      </div>
    @endforeach
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route($route.'.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
