<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SrtStream;
use Symfony\Component\Process\Process;

class StreamWatcher extends Command
{
    protected $signature = 'stream:watch';
    protected $description = 'Vigila puertos y arranca FFmpeg si hay señal';

    public function handle()
    {
        $streams = SrtStream::all();

        foreach ($streams as $stream) {
            // Verificamos si hay alguien conectado al puerto (LISTENING)
            $cmd = "netstat -an | findstr :{$stream->puerto} | findstr LISTENING";
            $process = new Process([$cmd]);
            $process->run();

            $estaActivo = !empty($process->getOutput());

            if ($estaActivo && $stream->estado !== 'activo') {
                $this->info("¡Señal detectada en {$stream->nombre}! Iniciando...");

                // Lanzamos el comando de encoding
                pclose(popen("start /B php artisan stream:encode {$stream->id}", "r"));

                $stream->update(['estado' => 'activo']);
            }
            elseif (!$estaActivo && $stream->estado === 'activo') {
                $this->info("Señal perdida en {$stream->nombre}.");
                $stream->update(['estado' => 'inactivo']);
            }
        }
    }
}
