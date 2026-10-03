<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipo_de_documento';
    protected $primaryKey = 'id_tipo_documento';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['descripcion'];
}
