<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'factura';
    protected $primaryKey = 'num_factura';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['num_factura','cod_cliente','nombre_empleado','fecha_facturacion','cod_formapago','total_factura','igv'];

    public function cliente() { return $this->belongsTo(Cliente::class, 'cod_cliente', 'documento'); }
    public function detalles() { return $this->hasMany(DetalleFactura::class, 'cod_factura', 'num_factura'); }
}
