<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SrtStream;

class CheckStreamStatus extends Command
{
    protected $signature = 'streams:check-status';
    protected $description = 'Verifica conexiones activas para streams SRT en modos Caller y Listener';

    public function handle()
    {
        $streams = SrtStream::all();

        foreach ($streams as $stream) {
            $puerto = $stream->puerto;
            $isOnline = false;

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Buscamos cualquier actividad activa de red asociada al puerto (tanto UDP como TCP si aplica)
                $output = @shell_exec("netstat -ano | findstr :{$puerto}");

                if ($output) {
                    $lines = explode("\n", trim($output));
                    foreach ($lines as $line) {
                        // Si el puerto aparece listado con actividad o enlazado de forma activa
                        if (!empty($line) && (str_contains($line, 'UDP') || str_contains($line, 'ESTABLISHED') || str_contains($line, 'LISTENING'))) {
                            // Validamos que realmente tenga tráfico o una conexión asociada
                            $isOnline = true;
                            break;
                        }
                    }
                }
            } else {
                // Validación para entornos Linux
                $output = @shell_exec("netstat -an | grep :{$puerto}");
                if ($output && trim($output) !== '') {
                    $isOnline = true;
                }
            }

            $nuevoEstado = $isOnline ? 'ONLINE' : 'OFFLINE';

            if ($stream->estado !== $nuevoEstado) {
                $stream->update(['estado' => $nuevoEstado]);
                $this->info("Stream '{$stream->nombre}' (Puerto {$puerto}) [Modo: {$stream->modo}] actualizado a: {$nuevoEstado}");
            }
        }

        $this->info('Verificación completa de streams SRT.');
    }
}
