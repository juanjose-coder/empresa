<?php
namespace App\Http\Controllers;

use App\Models\{Cliente, TipoDocumento, Ciudad};

class ClienteController extends CrudController
{
    protected $model = Cliente::class;
    protected $route = 'cliente';
    protected $title = 'Clientes';

    protected function fields(): array
    {
        return [
            ['name'=>'documento','label'=>'Documento','type'=>'text','rules'=>'required|max:15|unique:cliente,documento'],
            ['name'=>'cod_tipo_documento','label'=>'Tipo doc.','type'=>'select','rules'=>'required','options'=>TipoDocumento::pluck('descripcion','id_tipo_documento')],
            ['name'=>'nombres','label'=>'Nombres','type'=>'text','rules'=>'required|max:30'],
            ['name'=>'apellidos','label'=>'Apellidos','type'=>'text','rules'=>'required|max:30'],
            ['name'=>'direccion','label'=>'Dirección','type'=>'text','rules'=>'nullable|max:20'],
            ['name'=>'cod_ciudad','label'=>'Ciudad','type'=>'select','rules'=>'required','options'=>Ciudad::pluck('nombre_ciudad','codigo_ciudad')],
            ['name'=>'telefono','label'=>'Teléfono','type'=>'text','rules'=>'nullable|max:20'],
        ];
    }
}
