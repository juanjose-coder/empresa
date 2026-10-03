<?php
namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function edit()
    {
        return view('empresa.edit', ['empresa' => Empresa::first() ?? new Empresa]);
    }

    public function update(Request $request)
    {
        $d = $request->validate([
            'nombre'       => 'required|max:60',
            'ruc'          => 'required|digits:11',
            'razon_social' => 'required|max:80',
            'direccion'    => 'nullable|max:100',
            'telefono'     => 'nullable|max:20',
            'correo'       => 'nullable|email|max:60',
            'logo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $nombre = 'logo_empresa.' . $request->file('logo')->extension();
            $request->file('logo')->move(public_path('img'), $nombre);
            $d['logo'] = $nombre;
        } else {
            unset($d['logo']);
        }

        $empresa = Empresa::first() ?? new Empresa;
        $empresa->fill($d)->save();

        return back()->with('status', 'Datos de la empresa guardados');
    }
}