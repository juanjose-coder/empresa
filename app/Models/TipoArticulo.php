<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TipoArticulo extends Model
{
    protected $table = 'tipo_articulo';
    protected $primaryKey = 'id_tipoarticulo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['descripcion_articulo'];
}
