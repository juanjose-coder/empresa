<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devolucion';
    protected $primaryKey = 'cod_detallefactura';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['cod_detallefactura','cod_detallearticulo','motivo','fecha_devolucion','cantidad'];
}
