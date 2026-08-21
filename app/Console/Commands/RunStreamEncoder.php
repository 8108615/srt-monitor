<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stream;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;

class RunStreamEncoder extends Command
{
    protected $signature = 'stream:encode {stream_id}';
    protected $description = 'Inicia el proceso de FFmpeg para transcodificar un stream SRT a HLS';

    public function handle()
    {
        $stream = Stream::find($this->argument('stream_id'));

        if (!$stream) {
            $this->error('Stream no encontrado.');
            return 1;
        }

        // Directorio donde se guardarán los segmentos HLS públicos
        $outputDir = public_path("streams/{$stream->id}");

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Construir comando FFmpeg para recibir SRT y convertir a HLS de baja latencia
        $cmd = [
            'ffmpeg',
            '-i', "srt://0.0.0.0:{$stream->puerto}?mode=listener",
            '-c:v', 'libx264',
            '-preset', 'veryfast',
            '-tune', 'zerolatency',
            '-c:a', 'aac',
            '-b:a', '128k',
            '-f', 'hls',
            '-hls_time', '2',
            '-hls_list_size', '3',
            '-hls_flags', 'delete_segments',
            "{$outputDir}/playlist.m3u8"
        ];

        $this->info("Iniciando FFmpeg para el stream: {$stream->nombre} en el puerto {$stream->puerto}...");

        $process = new Process($cmd);
        $process->setTimeout(null); // Sin límite de tiempo por ser un stream en vivo

        // Actualizar estado a activo
        $stream->update(['estado' => 'activo']);

        $process->run(function ($type, $buffer) {
            // Puedes ver la salida de ffmpeg en la consola si ejecutas manualmente
            $this->output->write($buffer);
        });

        // Si el proceso se detiene, actualizamos el estado
        $stream->update(['estado' => 'inactivo']);

        return 0;
    }
}
