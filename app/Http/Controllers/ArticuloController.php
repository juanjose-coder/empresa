<?php
namespace App\Http\Controllers;

use App\Models\{Articulo, TipoArticulo, Proveedor};
use Illuminate\Http\Request;

class ArticuloController extends CrudController
{
    protected $model = Articulo::class;
    protected $route = 'articulo';
    protected $title = 'Artículos';

    protected function fields(): array
    {
        return [
            ['name'=>'descripcion','label'=>'Descripción','type'=>'text','rules'=>'required|max:30'],
            ['name'=>'precio_venta','label'=>'Precio venta','type'=>'number','rules'=>'required|integer|min:0'],
            ['name'=>'precio_costo','label'=>'Precio costo','type'=>'number','rules'=>'required|integer|min:0'],
            ['name'=>'stock','label'=>'Stock','type'=>'number','rules'=>'required|integer|min:0'],
            ['name'=>'cod_tipo_articulo','label'=>'Tipo','type'=>'select','rules'=>'required','options'=>TipoArticulo::pluck('descripcion_articulo','id_tipoarticulo')],
            ['name'=>'cod_proveedor','label'=>'Proveedor','type'=>'select','rules'=>'required','options'=>Proveedor::pluck('nombre_comercial','no_documento')],
            ['name'=>'fecha_ingreso','label'=>'Fecha ingreso','type'=>'date','rules'=>'nullable|date'],
        ];
    }

    /** Actualizar stock (sumar unidades al inventario) */
    public function stock(Request $request, $id)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);
        Articulo::findOrFail($id)->increment('stock', $request->cantidad);
        return back()->with('status', 'Stock actualizado');
    }
}
