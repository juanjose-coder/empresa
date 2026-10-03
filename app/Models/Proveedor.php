<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'no_documento';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['no_documento','cod_tipo_documento','nombre','apellido','nombre_comercial','direccion','cod_ciudad','telefono'];
}
