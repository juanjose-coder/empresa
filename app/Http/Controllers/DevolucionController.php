<?php
namespace App\Http\Controllers;

use App\Models\{Devolucion, DetalleFactura, Factura, Articulo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionController extends Controller
{
    /** Motivos disponibles (la columna `motivo` admite hasta 15 caracteres). */
    const MOTIVOS = ['Defectuoso', 'Dañado', 'Error de pedido', 'Otro'];

    /** Una fila por factura con devoluciones (sin repetir números). */
    public function index()
    {
        $devoluciones = Devolucion::select('cod_detallefactura', DB::raw('MAX(fecha_devolucion) as fecha'))
            ->groupBy('cod_detallefactura')
            ->orderByDesc('fecha')->orderByDesc('cod_detallefactura')
            ->get();

        return view('devolucion.index', compact('devoluciones'));
    }

    /** Detalle: artículos devueltos de una factura. */
    public function show($factura)
    {
        $items = Devolucion::where('cod_detallefactura', $factura)->get();
        abort_if($items->isEmpty(), 404);

        return view('devolucion.show', [
            'factura' => Factura::with('cliente')->findOrFail($factura),
            'items'   => $items,
            'arts'    => Articulo::pluck('descripcion', 'id_articulo'),
        ]);
    }

    public function create(Request $request)
    {
        $facturas = Factura::with('cliente')->orderByDesc('fecha_facturacion')->orderByDesc('num_factura')->get();
        $factura = null;
        $yaDevuelta = false;
        $lineas = collect();

        if ($request->filled('factura')) {
            $factura = Factura::with('cliente')->find($request->factura);
            if ($factura) {
                $yaDevuelta = Devolucion::where('cod_detallefactura', $factura->num_factura)->exists();

                if (!$yaDevuelta) {
                    $arts = Articulo::pluck('descripcion', 'id_articulo');
                    $lineas = DetalleFactura::where('cod_factura', $factura->num_factura)->get()
                        ->map(fn($d) => (object) [
                            'id'          => $d->cod_articulo,
                            'descripcion' => $arts[$d->cod_articulo] ?? $d->cod_articulo,
                            'vendido'     => $d->cantidad,
                        ]);
                }
            }
        }

        return view('devolucion.create', ['facturas' => $facturas, 'factura' => $factura,
            'yaDevuelta' => $yaDevuelta, 'lineas' => $lineas, 'motivos' => self::MOTIVOS]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod_detallefactura' => 'required|exists:factura,num_factura',
            'motivo'             => 'required|in:' . implode(',', self::MOTIVOS),
            'items'              => 'required|array',
            'items.*'            => 'nullable|integer|min:0',
        ]);

        if (Devolucion::where('cod_detallefactura', $request->cod_detallefactura)->exists()) {
            return back()->withInput()->withErrors('Esta factura ya tuvo devoluciones. Elija otra.');
        }

        // Solo los artículos con cantidad mayor a 0
        $items = collect($request->items)->filter(fn($c) => (int) $c > 0);
        if ($items->isEmpty()) {
            return back()->withInput()->withErrors('Indica la cantidad a devolver de al menos un artículo.');
        }

        try {
            DB::transaction(function () use ($request, $items) {
                $num = $request->cod_detallefactura;
                $detalles = DetalleFactura::where('cod_factura', $num)->get()->keyBy('cod_articulo');

                foreach ($items as $idArticulo => $cantidad) {
                    $det = $detalles->get((int) $idArticulo);
                    if (!$det) {
                        throw new \RuntimeException('Uno de los artículos no pertenece a esa factura.');
                    }
                    if ($cantidad > $det->cantidad) {
                        throw new \RuntimeException("No se puede devolver más de lo vendido ({$det->cantidad} unidades).");
                    }
                    if (Devolucion::where('cod_detallefactura', $num)->where('cod_detallearticulo', $idArticulo)->exists()) {
                        throw new \RuntimeException('Uno de los artículos ya fue devuelto en esta factura.');
                    }

                    Devolucion::create([
                        'cod_detallefactura'  => $num,
                        'cod_detallearticulo' => $idArticulo,
                        'motivo'              => $request->motivo,
                        'fecha_devolucion'    => date('Y-m-d'),
                        'cantidad'            => $cantidad,
                    ]);
                    Articulo::where('id_articulo', $idArticulo)->increment('stock', $cantidad);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('devolucion.show', $request->cod_detallefactura)->with('status', 'Devolución registrada');
    }
}
