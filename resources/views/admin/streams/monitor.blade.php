<x-layouts::app title="Monitoreo: {{ $stream->nombre }}">
    <div class="p-6">
        <flux:heading size="xl">{{ $stream->nombre }}</flux:heading>
        <p class="text-gray-500">Estado: <span id="estado-stream">{{ $stream->estado }}</span> | Puerto: {{ $stream->puerto }}</p>

        <div class="grid grid-cols-3 gap-6 mt-6">
            <!-- Reproductor de Video -->
            <div class="col-span-2 bg-black rounded-lg overflow-hidden aspect-video flex items-center justify-center">
                @if($videoDisponible)
                    <video id="video-player" class="w-full h-full" controls autoplay muted></video>
                @else
                    <div class="text-white text-center p-6">
                        <x-flux::icon.video-camera class="w-16 h-16 mx-auto mb-4 text-gray-500" />
                        <p class="text-lg">Esperando señal de video...</p>
                        <p class="text-sm text-gray-400">El proceso de encoder está inicializando. Por favor, espera unos segundos.</p>
                    </div>
                @endif
            </div>

            <!-- Panel de Métricas -->
            <div class="col-span-1 bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200">
                <h3 class="font-bold text-lg mb-4">Métricas en Tiempo Real</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Bitrate</p>
                        <p class="text-2xl font-mono" id="bitrate-val">{{ $stream->bitrate ?? 0 }} Mbps</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pérdida de paquetes</p>
                        <p class="text-2xl font-mono" id="loss-val">{{ $stream->perdida_paquetes }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($videoDisponible)
        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        <script>
            var video = document.getElementById('video-player');
            var videoSrc = '/streams/{{ $stream->id }}/playlist.m3u8';

            if (Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(videoSrc);
                hls.attachMedia(video);
            }
        </script>
    @endif
</x-layouts::app>
