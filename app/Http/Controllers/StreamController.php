<?php

namespace App\Http\Controllers;

use App\Models\SrtStream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $streams = SrtStream::when($buscar, function ($query, $buscar) {
            return $query->where('nombre', 'LIKE', "%{$buscar}%")
                        ->orWhere('url_srt', 'LIKE', "%{$buscar}%");
        })->paginate(10);

        return view('admin.streams.index', compact('streams'));
    }

    public function create()
    {
        return view('admin.streams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'url_srt' => 'required|string|max:255',
            'puerto' => 'required|integer|min:1|max:65535',
            'modo' => 'required|in:listener,caller',
        ]);

        try {
            SrtStream::create([
                'nombre' => $request->nombre,
                'url_srt' => $request->url_srt,
                'puerto' => $request->puerto,
                'modo' => $request->modo,
                'estado' => 'OFFLINE', // Por defecto inicia desconectado hasta que el monitor valide señal
                'perdida_paquetes' => 0,
            ]);

            return redirect()->route('admin.streams.index')
                ->with('mensaje', 'Stream SRT registrado correctamente.')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('mensaje', 'Ocurrió un error al registrar el stream: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function show($id)
    {
        // Mostrar detalles
    }

    public function edit($id)
    {
        // Vista de edición
    }

    public function update(Request $request, $id)
    {
        // Actualizar datos
    }

    public function destroy($id)
    {
        // Eliminar stream
    }
}
