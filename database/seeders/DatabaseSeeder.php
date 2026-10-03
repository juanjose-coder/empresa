<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_de_documento')->insert([['descripcion'=>'DNI'],['descripcion'=>'RUC'],['descripcion'=>'CE']]);
        DB::table('ciudad')->insert([['nombre_ciudad'=>'Lima'],['nombre_ciudad'=>'Arequipa'],['nombre_ciudad'=>'Cusco']]);
        DB::table('tipo_articulo')->insert([['descripcion_articulo'=>'Abarrotes'],['descripcion_articulo'=>'Electrónica'],['descripcion_articulo'=>'Limpieza']]);
        DB::table('forma_de_pago')->insert([['descripcion_formapago'=>'Efectivo'],['descripcion_formapago'=>'Tarjeta'],['descripcion_formapago'=>'Yape']]);
    }
}
