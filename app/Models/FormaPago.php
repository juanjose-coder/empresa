<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'forma_de_pago';
    protected $primaryKey = 'id_formapago';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = ['descripcion_formapago'];
}
