<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;
    protected $fillable = ['nombre','ruc','razon_social','direccion','telefono','correo','logo'];
}