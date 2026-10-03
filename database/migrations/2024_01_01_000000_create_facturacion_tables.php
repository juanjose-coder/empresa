<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipo_de_documento', function (Blueprint $t) {
            $t->increments('id_tipo_documento');
            $t->string('descripcion', 10);
        });
        Schema::create('ciudad', function (Blueprint $t) {
            $t->increments('codigo_ciudad');
            $t->string('nombre_ciudad', 30);
        });
        Schema::create('tipo_articulo', function (Blueprint $t) {
            $t->increments('id_tipoarticulo');
            $t->string('descripcion_articulo', 30);
        });
        Schema::create('forma_de_pago', function (Blueprint $t) {
            $t->increments('id_formapago');
            $t->string('descripcion_formapago', 20);
        });
        Schema::create('cliente', function (Blueprint $t) {
            $t->string('documento', 15)->primary();
            $t->unsignedInteger('cod_tipo_documento');
            $t->string('nombres', 30);
            $t->string('apellidos', 30);
            $t->string('direccion', 20)->nullable();
            $t->unsignedInteger('cod_ciudad');
            $t->string('telefono', 20)->nullable();
            $t->foreign('cod_tipo_documento')->references('id_tipo_documento')->on('tipo_de_documento');
            $t->foreign('cod_ciudad')->references('codigo_ciudad')->on('ciudad');
        });
        Schema::create('proveedor', function (Blueprint $t) {
            $t->string('no_documento', 20)->primary();
            $t->unsignedInteger('cod_tipo_documento');
            $t->string('nombre', 20);
            $t->string('apellido', 20);
            $t->string('nombre_comercial', 20);
            $t->string('direccion', 20)->nullable();
            $t->unsignedInteger('cod_ciudad');
            $t->string('telefono', 15)->nullable();
            $t->foreign('cod_tipo_documento')->references('id_tipo_documento')->on('tipo_de_documento');
            $t->foreign('cod_ciudad')->references('codigo_ciudad')->on('ciudad');
        });
        Schema::create('articulo', function (Blueprint $t) {
            $t->increments('id_articulo');
            $t->string('descripcion', 30);
            $t->integer('precio_venta');
            $t->integer('precio_costo');
            $t->integer('stock')->default(0);
            $t->unsignedInteger('cod_tipo_articulo');
            $t->string('cod_proveedor', 20);
            $t->date('fecha_ingreso')->nullable();
            $t->foreign('cod_tipo_articulo')->references('id_tipoarticulo')->on('tipo_articulo');
            $t->foreign('cod_proveedor')->references('no_documento')->on('proveedor');
        });
        Schema::create('factura', function (Blueprint $t) {
            $t->string('num_factura', 20)->primary();
            $t->string('cod_cliente', 15);
            $t->string('nombre_empleado', 30);
            $t->date('fecha_facturacion');
            $t->unsignedInteger('cod_formapago');
            $t->decimal('igv', 10, 2);
            $t->decimal('total_factura', 10, 2);   
            $t->foreign('cod_cliente')->references('documento')->on('cliente');
            $t->foreign('cod_formapago')->references('id_formapago')->on('forma_de_pago');
        });
        Schema::create('detalle_factura', function (Blueprint $t) {
            $t->string('cod_factura', 20);
            $t->unsignedInteger('cod_articulo');
            $t->integer('cantidad');
            $t->decimal('total', 10, 2);
            $t->primary(['cod_factura', 'cod_articulo']);
            $t->foreign('cod_factura')->references('num_factura')->on('factura');
            $t->foreign('cod_articulo')->references('id_articulo')->on('articulo');
        });
      Schema::create('devolucion', function (Blueprint $t) {
    $t->string('cod_detallefactura', 20);
    $t->unsignedInteger('cod_detallearticulo');
    $t->string('motivo', 15);
    $t->date('fecha_devolucion');
    $t->integer('cantidad');
    $t->primary(['cod_detallefactura', 'cod_detallearticulo']);

    $t->foreign(['cod_detallefactura', 'cod_detallearticulo'])
      ->references(['cod_factura', 'cod_articulo'])
      ->on('detalle_factura');
});
    }
    public function down(): void
    {
        foreach (['devolucion','detalle_factura','factura','articulo','proveedor','cliente','forma_de_pago','tipo_articulo','ciudad','tipo_de_documento'] as $t) Schema::dropIfExists($t);
    }
};
