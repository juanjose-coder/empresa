<?php
namespace App\Http\Controllers;

use App\Models\{Proveedor, TipoDocumento, Ciudad};

class ProveedorController extends CrudController
{
    protected $model = Proveedor::class;
    protected $route = 'proveedor';
    protected $title = 'Proveedores';

    protected function fields(): array
    {
        return [
            ['name'=>'no_documento','label'=>'Documento','type'=>'text','rules'=>'required|max:20|unique:proveedor,no_documento'],
            ['name'=>'cod_tipo_documento','label'=>'Tipo doc.','type'=>'select','rules'=>'required','options'=>TipoDocumento::pluck('descripcion','id_tipo_documento')],
            ['name'=>'nombre','label'=>'Nombre','type'=>'text','rules'=>'required|max:20'],
            ['name'=>'apellido','label'=>'Apellido','type'=>'text','rules'=>'required|max:20'],
            ['name'=>'nombre_comercial','label'=>'Nombre comercial','type'=>'text','rules'=>'required|max:20'],
            ['name'=>'direccion','label'=>'Dirección','type'=>'text','rules'=>'nullable|max:20'],
            ['name'=>'cod_ciudad','label'=>'Ciudad','type'=>'select','rules'=>'required','options'=>Ciudad::pluck('nombre_ciudad','codigo_ciudad')],
            ['name'=>'telefono','label'=>'Teléfono','type'=>'text','rules'=>'nullable|max:15'],
        ];
    }
}
