<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulo';
    protected $primaryKey = 'id_articulo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['descripcion','precio_venta','precio_costo','stock','cod_tipo_articulo','cod_proveedor','fecha_ingreso'];
}
