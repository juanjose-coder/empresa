<?php
namespace App\Http\Controllers;

use App\Models\{Factura, DetalleFactura, Cliente, FormaPago, Articulo, Empresa, TipoDocumento};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth};

class FacturaController extends Controller
{
    const IGV = 0.18;
    const SERIE = 'F001';

    public function index()
    {
        return view('factura.index', ['facturas' => Factura::with('cliente')->orderByDesc('fecha_facturacion')->orderByDesc('num_factura')->get()]);
    }

    public function create()
    {
        return view('factura.create', [
            'clientes' => Cliente::all(),
            'pagos' => FormaPago::all(),
            'articulos' => Articulo::where('stock', '>', 0)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod_cliente' => 'required|exists:cliente,documento',
            'cod_formapago' => 'required|exists:forma_de_pago,id_formapago',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:articulo,id_articulo|distinct',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            $num = DB::transaction(function () use ($request) {
                $subtotal = 0;
                $lineas = [];
                foreach ($request->items as $it) {
                    $art = Articulo::lockForUpdate()->findOrFail($it['id']);
                    if ($art->stock < $it['cantidad']) {
                        throw new \RuntimeException("Stock insuficiente de {$art->descripcion} (disponible {$art->stock})");
                    }
                    $total = $art->precio_venta * $it['cantidad'];
                    $subtotal += $total;
                    $lineas[] = [$art, $it['cantidad'], $total];
                }
                $igv = round($subtotal * self::IGV, 2);

                // Correlativo consecutivo de la serie: F001-00000001
                $ultimo = Factura::where('num_factura', 'like', self::SERIE . '-%')
                    ->orderByDesc('num_factura')->lockForUpdate()->value('num_factura');
                $correlativo = $ultimo ? ((int) substr($ultimo, 5)) + 1 : 1;
                $num = self::SERIE . '-' . str_pad((string) $correlativo, 8, '0', STR_PAD_LEFT);

                Factura::create([
                    'num_factura' => $num, 'cod_cliente' => $request->cod_cliente,
                    'nombre_empleado' => Auth::user()->name, 'fecha_facturacion' => date('Y-m-d'),
                    'cod_formapago' => $request->cod_formapago,
                    'total_factura' => $subtotal + $igv, 'igv' => $igv,
                ]);
                foreach ($lineas as [$art, $cant, $total]) {
                    DetalleFactura::create(['cod_factura' => $num, 'cod_articulo' => $art->id_articulo, 'cantidad' => $cant, 'total' => $total]);
                    $art->decrement('stock', $cant);
                }
                return $num;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('factura.show', $num)->with('status', 'Factura generada');
    }

    public function show($id)
    {
        $factura = Factura::with('cliente', 'detalles')->findOrFail($id);
        $arts = Articulo::pluck('descripcion', 'id_articulo');
        $empresa = Empresa::first();
        $pago = FormaPago::where('id_formapago', $factura->cod_formapago)->value('descripcion_formapago');
        $tipoDoc = $factura->cliente
            ? TipoDocumento::where('id_tipo_documento', $factura->cliente->cod_tipo_documento)->value('descripcion')
            : null;

        return view('factura.show', compact('factura', 'arts', 'empresa', 'pago', 'tipoDoc'));
    }
}
