<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SrtStream;

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
            'modo' => 'required|in:listener,caller',
            // Si es caller la IP es obligatoria, si es listener puede ir vacía o tener un valor genérico
            'url_srt' => $request->modo == 'caller' ? 'required|string|max:255' : 'nullable|string|max:255',
            'puerto' => 'required|integer|min:1|max:65535',
            'latencia' => 'required|integer|min:0',
            'usar_passphrase' => 'nullable',
            'passphrase' => $request->has('usar_passphrase') ? 'required|string|min:4|max:100' : 'nullable',
        ]);

        try {
            SrtStream::create([
                'nombre' => $request->nombre,
                'modo' => $request->modo,
                // Si es listener guardamos un texto descriptivo o dejamos el campo según prefieras; si es caller guardamos la IP indicada
                'url_srt' => $request->modo == 'caller' ? $request->url_srt : 'Escuchando en puerto local',
                'puerto' => $request->puerto,
                'latencia' => $request->latencia,
                'usar_passphrase' => $request->has('usar_passphrase') ? true : false,
                'passphrase' => $request->has('usar_passphrase') ? $request->passphrase : null,
                'estado' => 'OFFLINE',
                'perdida_paquetes' => 0,
                'tiene_audio' => true,
                'tiene_video' => true,
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
        $stream = SrtStream::findOrFail($id);
        return view('admin.streams.show', compact('stream'));
    }

    public function edit($id)
    {
        $stream = SrtStream::findOrFail($id);
        return view('admin.streams.edit', compact('stream'));
    }

    public function update(Request $request, $id)
    {
        $stream = SrtStream::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'modo' => 'required|in:listener,caller',
            'url_srt' => $request->modo == 'caller' ? 'required|string|max:255' : 'nullable|string|max:255',
            'puerto' => 'required|integer|min:1|max:65535',
            'latencia' => 'required|integer|min:0',
            'usar_passphrase' => 'nullable',
            'passphrase' => $request->has('usar_passphrase') ? 'required|string|min:4|max:100' : 'nullable',
        ]);

        try {
            $stream->update([
                'nombre' => $request->nombre,
                'modo' => $request->modo,
                'url_srt' => $request->modo == 'caller' ? $request->url_srt : 'Escuchando en puerto local',
                'puerto' => $request->puerto,
                'latencia' => $request->latencia,
                'usar_passphrase' => $request->has('usar_passphrase') ? true : false,
                'passphrase' => $request->has('usar_passphrase') ? $request->passphrase : null,
            ]);

            return redirect()->route('admin.streams.index')
                ->with('mensaje', 'Stream SRT actualizado correctamente.')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('mensaje', 'Ocurrió un error al actualizar el stream: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function destroy($id)
    {
        $stream = SrtStream::findOrFail($id);
        $stream->delete();
        return redirect()->route('admin.streams.index')
            ->with('mensaje', 'Stream eliminado con éxito.')
            ->with('icono', 'success');
    }

    public function monitor($id)
    {
        $stream = SrtStream::findOrFail($id);

        // 1. Definimos la ruta donde FFmpeg debería estar dejando los archivos
        $playlistPath = public_path("streams/{$stream->id}/playlist.m3u8");

        // 2. Verificamos si el archivo existe (esto indica que hay señal y el encoder trabaja)
        $videoDisponible = file_exists($playlistPath);

        // 3. Pasamos la variable a la vista para que sepa si mostrar el video o un mensaje
        return view('admin.streams.monitor', compact('stream', 'videoDisponible'));
    }
    public function restartWatcher($id)
    {
        $stream = SrtStream::findOrFail($id);

        try {
            // 1. Opcional: Matar cualquier proceso de FFmpeg asociado a este stream/puerto específico en Windows
            // Esto busca tareas que contengan el puerto del stream y las fuerza a cerrar
            exec("taskkill /f /fi \"SERVICES eq none\" /im ffmpeg.exe");
            // O de manera más directa si prefieres matar procesos colgados de ffmpeg:
            // exec("wmic process where \"commandlike like '%{$stream->puerto}%'\" call terminate");

            // 2. Actualizamos el estado para reflejar el reinicio
            $stream->update([
                'estado' => 'OFFLINE',
                'perdida_paquetes' => 0
            ]);

            return redirect()->route('admin.streams.index')
                ->with('mensaje', "El stream '{$stream->nombre}' ha sido reiniciado correctamente.")
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al reiniciar el stream: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}
