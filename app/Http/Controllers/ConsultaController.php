<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function index()
    {
        $bajoStock = DB::table('articulo')->where('stock', '<=', 5)->orderBy('stock')->get();
        $ventasCliente = DB::table('factura')
            ->join('cliente', 'cliente.documento', '=', 'factura.cod_cliente')
            ->select('cliente.nombres', 'cliente.apellidos', DB::raw('COUNT(*) as facturas'), DB::raw('SUM(total_factura) as total'))
            ->groupBy('cliente.nombres', 'cliente.apellidos')->orderByDesc('total')->get();
        return view('consultas', compact('bajoStock', 'ventasCliente'));
    }
}
