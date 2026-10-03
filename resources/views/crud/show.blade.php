@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="container" style="max-width:640px">
  <h2>Detalle · {{ $title }}</h2>
  <table class="table">
    @foreach($fields as $f)
      <tr><th>{{ $f['label'] }}</th>
      <td>{{ $f['type']=='select' ? ($f['options'][$item->{$f['name']}] ?? $item->{$f['name']}) : $item->{$f['name']} }}</td></tr>
    @endforeach
  </table>
  <a href="{{ route($route.'.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
