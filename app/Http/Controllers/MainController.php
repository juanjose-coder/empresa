<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function showHome()
    {
        $stats = null; $ultimas = collect(); $graf = [];
        if (auth()->check()) {
            $stats = [
                'clientes'    => DB::table('cliente')->count(),
                'articulos'   => DB::table('articulo')->count(),
                'proveedores' => DB::table('proveedor')->count(),
                'facturas'    => DB::table('factura')->count(),
                'ventas'      => DB::table('factura')->sum('total_factura'),
                'bajo'        => DB::table('articulo')->where('stock', '<=', 5)->count(),
            ];
            $ultimas = DB::table('factura')->orderByDesc('fecha_facturacion')->limit(5)->get();

            // Gráfico 1: ventas por mes
            $graf['meses'] = DB::table('factura')
                ->selectRaw("DATE_FORMAT(fecha_facturacion,'%Y-%m') as mes, SUM(total_factura) as total")
                ->groupBy('mes')->orderBy('mes')->limit(12)->get();
            // Gráfico 2: artículos más vendidos
            $graf['top'] = DB::table('detalle_factura')
                ->join('articulo', 'articulo.id_articulo', '=', 'detalle_factura.cod_articulo')
                ->selectRaw('articulo.descripcion as nombre, SUM(detalle_factura.cantidad) as total')
                ->groupBy('articulo.descripcion')->orderByDesc('total')->limit(5)->get();
            // Gráfico 3: stock por tipo de artículo
            $graf['stock'] = DB::table('articulo')
                ->join('tipo_articulo', 'tipo_articulo.id_tipoarticulo', '=', 'articulo.cod_tipo_articulo')
                ->selectRaw('tipo_articulo.descripcion_articulo as nombre, SUM(articulo.stock) as total')
                ->groupBy('tipo_articulo.descripcion_articulo')->get();
        }
        return view('home', compact('stats', 'ultimas', 'graf'));
    }

    public function showAbout() { return view('about'); }
}
