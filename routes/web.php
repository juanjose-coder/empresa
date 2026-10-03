<?php
use Illuminate\Support\Facades\{Route, Auth};
use App\Http\Controllers\{MainController, ClienteController, ArticuloController, ProveedorController,
    FacturaController, DevolucionController, ConsultaController};

// Públicas
Route::get('/', [MainController::class, 'showHome'])->name('inicio');
Route::get('/about', [MainController::class, 'showAbout'])->name('about');

Auth::routes();                                            // login, registro, logout, contraseñas
Route::get('/home', fn() => redirect()->route('inicio'));  // destino tras login

// Protegidas (requieren sesión)
Route::middleware('auth')->group(function () {
    Route::resource('cliente', ClienteController::class);
    Route::resource('proveedor', ProveedorController::class);
    Route::resource('articulo', ArticuloController::class);
    Route::post('articulo/{id}/stock', [ArticuloController::class, 'stock'])->name('articulo.stock');
    Route::resource('factura', FacturaController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('devolucion', DevolucionController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('consultas', [ConsultaController::class, 'index'])->name('consultas');
    Route::get('empresa', [\App\Http\Controllers\EmpresaController::class, 'edit'])->name('empresa');
    Route::put('empresa', [\App\Http\Controllers\EmpresaController::class, 'update'])->name('empresa.update');
});
