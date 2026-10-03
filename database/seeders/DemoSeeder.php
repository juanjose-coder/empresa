<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Catálogos (ids fijos)
        DB::table('tipo_de_documento')->insertOrIgnore([
            ['id_tipo_documento'=>1,'descripcion'=>'DNI'],
            ['id_tipo_documento'=>2,'descripcion'=>'RUC'],
        ]);
        DB::table('ciudad')->insertOrIgnore([
            ['codigo_ciudad'=>1,'nombre_ciudad'=>'Lima'],
            ['codigo_ciudad'=>2,'nombre_ciudad'=>'Cusco'],
            ['codigo_ciudad'=>3,'nombre_ciudad'=>'Arequipa'],
        ]);
        DB::table('tipo_articulo')->insertOrIgnore([
            ['id_tipoarticulo'=>1,'descripcion_articulo'=>'Alimentos'],
            ['id_tipoarticulo'=>2,'descripcion_articulo'=>'Tecnología'],
            ['id_tipoarticulo'=>3,'descripcion_articulo'=>'Limpieza'],
        ]);
        DB::table('forma_de_pago')->insertOrIgnore([
            ['id_formapago'=>1,'descripcion_formapago'=>'Efectivo'],
            ['id_formapago'=>2,'descripcion_formapago'=>'Tarjeta'],
            ['id_formapago'=>3,'descripcion_formapago'=>'Yape'],
        ]);

        // Empresa emisora (si ya la registraste desde el sistema, se respeta)
        DB::table('empresa')->insertOrIgnore([
            'id_empresa'=>1,'nombre'=>'Distribuidora Pedro','ruc'=>'20123456789',
            'razon_social'=>'Distribuidora Pedro S.A.C.','direccion'=>'Av. Principal 123',
            'telefono'=>'999888777','correo'=>'contacto@empresa.com',
        ]);

        // Limpia solo datos de ventas y artículos (no toca usuarios)
        DB::table('devolucion')->delete();
        DB::table('detalle_factura')->delete();
        DB::table('factura')->delete();
        DB::table('articulo')->delete();

        DB::table('proveedor')->insertOrIgnore([
            ['no_documento'=>'20100000001','cod_tipo_documento'=>2,'nombre'=>'Luis','apellido'=>'Rojas','nombre_comercial'=>'Distribuidora Sol','direccion'=>'Av. Lima 123','cod_ciudad'=>1,'telefono'=>'999111222'],
            ['no_documento'=>'20100000002','cod_tipo_documento'=>2,'nombre'=>'Ana','apellido'=>'Paredes','nombre_comercial'=>'TecnoPeru','direccion'=>'Jr. Cusco 45','cod_ciudad'=>2,'telefono'=>'988777666'],
        ]);

        // Clientes (todos con RUC, porque se les emite factura)
        DB::table('cliente')->insertOrIgnore([
            ['documento'=>'20601234567','cod_tipo_documento'=>2,'nombres'=>'Comercial Andina','apellidos'=>'S.A.C.','direccion'=>'Av. Grau 450','cod_ciudad'=>1,'telefono'=>'911111111'],
            ['documento'=>'20512345678','cod_tipo_documento'=>2,'nombres'=>'Ferreteria Norte','apellidos'=>'E.I.R.L.','direccion'=>'Jr. Puno 88','cod_ciudad'=>2,'telefono'=>'922222222'],
            ['documento'=>'20456789123','cod_tipo_documento'=>2,'nombres'=>'Bodega San Jose','apellidos'=>'E.I.R.L.','direccion'=>'Calle 3 N 120','cod_ciudad'=>3,'telefono'=>'933333333'],
        ]);

        // Artículos con STOCK INICIAL (se descuenta con cada venta, como en el sistema)
        $defs = [
            'Arroz 5kg'      => [25, 18, 100, 1, '20100000001'],
            'Aceite 1L'      => [9,  6,  80,  1, '20100000001'],
            'Mouse USB'      => [30, 18, 30,  2, '20100000002'],
            'Teclado'        => [60, 40, 18,  2, '20100000002'],
            'Detergente 2kg' => [14, 9,  20,  3, '20100000001'],
        ];
        $art = [];
        foreach ($defs as $nombre => [$pv, $pc, $stock, $tipo, $prov]) {
            $id = DB::table('articulo')->insertGetId([
                'descripcion'=>$nombre,'precio_venta'=>$pv,'precio_costo'=>$pc,'stock'=>$stock,
                'cod_tipo_articulo'=>$tipo,'cod_proveedor'=>$prov,'fecha_ingreso'=>now()->subMonths(6)->format('Y-m-d'),
            ]);
            $art[$nombre] = ['id'=>$id,'pv'=>$pv];
        }

        $pagos = DB::table('forma_de_pago')->pluck('id_formapago')->values();

        // [meses atrás, día, cliente, [artículo => cantidad]]
        $ventas = [
            [5, 5,  '20601234567', ['Arroz 5kg'=>4,  'Aceite 1L'=>6]],
            [4, 10, '20512345678', ['Mouse USB'=>3,  'Teclado'=>2]],
            [4, 20, '20601234567', ['Arroz 5kg'=>6,  'Detergente 2kg'=>5]],
            [3, 8,  '20456789123',['Aceite 1L'=>10, 'Mouse USB'=>5]],
            [3, 22, '20512345678', ['Teclado'=>4,    'Detergente 2kg'=>8]],
            [2, 12, '20601234567', ['Arroz 5kg'=>10, 'Aceite 1L'=>8]],
            [2, 25, '20456789123',['Mouse USB'=>6,  'Teclado'=>3]],
            [1, 6,  '20512345678', ['Arroz 5kg'=>8,  'Detergente 2kg'=>4]],
            [1, 18, '20601234567', ['Mouse USB'=>10, 'Teclado'=>5]],
            [0, 0,  '20456789123',['Arroz 5kg'=>5,  'Aceite 1L'=>7, 'Mouse USB'=>4]],  // hoy
        ];

        foreach ($ventas as $i => [$meses, $dia, $cliente, $items]) {
            $fecha = $meses === 0
                ? now()->format('Y-m-d')
                : now()->startOfMonth()->subMonths($meses)->day($dia)->format('Y-m-d');

            $num = 'F001-' . str_pad((string) ($i + 1), 8, '0', STR_PAD_LEFT);

            $sub = 0; $detalle = [];
            foreach ($items as $nombre => $cant) {
                $tot = $art[$nombre]['pv'] * $cant;
                $sub += $tot;
                $detalle[] = ['cod_factura'=>$num,'cod_articulo'=>$art[$nombre]['id'],'cantidad'=>$cant,'total'=>$tot];
                DB::table('articulo')->where('id_articulo', $art[$nombre]['id'])->decrement('stock', $cant);
            }
            $igv = round($sub * 0.18, 2);

            DB::table('factura')->insert([
                'num_factura'=>$num,'cod_cliente'=>$cliente,'nombre_empleado'=>'pedro',
                'fecha_facturacion'=>$fecha,'cod_formapago'=>$pagos[$i % $pagos->count()],
                'total_factura'=>$sub + $igv,'igv'=>$igv,
            ]);
            DB::table('detalle_factura')->insert($detalle);
        }
    }
}
