<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
   Schema::create('empresa', function (Blueprint $t) {
    $t->increments('id_empresa');
    $t->string('nombre', 60);
    $t->string('ruc', 11);
    $t->string('razon_social', 80);
    $t->string('direccion', 100)->nullable();
    $t->string('telefono', 20)->nullable();
    $t->string('correo', 60)->nullable();
    $t->string('logo', 100)->nullable();
});
}
public function down(): void { Schema::dropIfExists('empresa'); }
};
