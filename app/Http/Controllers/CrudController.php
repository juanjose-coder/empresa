<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/** Controlador base tipo "resource": index, create, store, show, edit, update, destroy */
abstract class CrudController extends Controller
{
    protected $model;   // clase del modelo
    protected $route;   // nombre de la ruta resource
    protected $title;   // título del módulo

    /** [ ['name'=>, 'label'=>, 'type'=>text|number|date|select, 'rules'=>, 'options'=>[id=>texto]] ] */
    abstract protected function fields(): array;

    private function rules(): array
    {
        return collect($this->fields())->mapWithKeys(fn($f) => [$f['name'] => $f['rules']])->all();
    }

    private function data($item = null): array
    {
        return ['fields' => $this->fields(), 'route' => $this->route, 'title' => $this->title, 'item' => $item];
    }

    public function index(Request $request)
    {
        $q = $this->model::query();
        if ($request->filled('q')) {          // búsqueda
            $names = collect($this->fields())->where('type', 'text')->pluck('name');
            $q->where(function ($w) use ($names, $request) {
                foreach ($names as $n) $w->orWhere($n, 'like', '%' . $request->q . '%');
            });
        }
        return view('crud.index', $this->data() + ['items' => $q->get(), 'search' => $request->q]);
    }

    public function create() { return view('crud.form', $this->data()); }

    public function store(Request $request)
    {
        $this->model::create($request->validate($this->rules()));
        return redirect()->route($this->route . '.index')->with('status', 'Registro creado');
    }

    public function show($id) { return view('crud.show', $this->data($this->model::findOrFail($id))); }

    public function edit($id) { return view('crud.form', $this->data($this->model::findOrFail($id))); }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $rules = $this->rules();
        $pk = $item->getKeyName();
        if (!$item->incrementing) unset($rules[$pk]);   // la llave no se edita
        $item->update($request->validate($rules));
        return redirect()->route($this->route . '.index')->with('status', 'Registro actualizado');
    }

    public function destroy($id)
    {
        try {
            $this->model::findOrFail($id)->delete();
            return back()->with('status', 'Registro eliminado');
        } catch (\Exception $e) {
            return back()->withErrors('No se puede eliminar: tiene registros relacionados.');
        }
    }
}
