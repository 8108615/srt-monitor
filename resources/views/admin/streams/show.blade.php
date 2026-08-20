<x-layouts::app title="Detalles del Stream SRT">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Detalles del Enlace: {{ $stream->nombre }}</flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configuración técnica y parámetros de red para el flujo SRT.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.streams.edit', $stream->id) }}"
               class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-1.5">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.streams.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Columna Principal: Información Técnica -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-md space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2 border-gray-200 dark:border-zinc-700">
                    <i class="fas fa-network-wired text-blue-500 mr-2"></i> Parámetros de Red
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase">Estado Actual</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 {{ $stream->estado == 'ONLINE' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $stream->estado == 'ONLINE' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $stream->estado }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase">Modo de Conexión</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 uppercase mt-1 block">
                            {{ $stream->modo }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase">Puerto SRT</span>
                        <span class="text-sm font-mono font-semibold text-blue-600 dark:text-blue-400 mt-1 block">
                            {{ $stream->puerto }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-400 uppercase">Latencia Configurada</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1 block">
                            {{ $stream->latencia }} ms
                        </span>
                    </div>

                    @if($stream->modo == 'caller')
                    <div class="col-span-2">
                        <span class="block text-xs font-medium text-gray-400 uppercase">IP / Host Remoto</span>
                        <span class="text-sm font-mono font-semibold text-gray-800 dark:text-gray-200 mt-1 block">
                            {{ $stream->url_srt }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Seguridad / Passphrase -->
            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-md space-y-3">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2 border-gray-200 dark:border-zinc-700">
                    <i class="fas fa-lock text-amber-500 mr-2"></i> Seguridad y Encriptación
                </h3>

                <div>
                    <span class="block text-xs font-medium text-gray-400 uppercase">Protección Passphrase</span>
                    @if($stream->usar_passphrase)
                        <span class="inline-block mt-1 text-sm text-green-600 dark:text-green-400 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Habilitada
                        </span>
                        <div class="mt-2 p-2.5 bg-gray-50 dark:bg-zinc-900 rounded border border-gray-200 dark:border-zinc-700 font-mono text-sm">
                            {{ $stream->passphrase }}
                        </div>
                    @else
                        <span class="inline-block mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-times-circle mr-1"></i> Sin encriptación de contraseña (Abierto)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Lateral: URL de Conexión Rápida -->
        <div class="space-y-6">
            @php
                $ipServidor = request()->getHost();
                $hostDestino = ($stream->modo == 'listener') ? $ipServidor : $stream->url_srt;
                $uri = "srt://{$hostDestino}:{$stream->puerto}";
                if($stream->usar_passphrase && $stream->passphrase) {
                    $uri .= "?passphrase=" . $stream->passphrase;
                }
            @endphp

            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-md space-y-4"
                 x-data="{
                     copied: false,
                     uri: '{{ $uri }}',
                     copyToClipboard() {
                         if (navigator.clipboard && navigator.clipboard.writeText) {
                             navigator.clipboard.writeText(this.uri).then(() => {
                                 this.triggerSuccess();
                             }).catch(() => {
                                 this.fallbackCopy();
                             });
                         } else {
                             this.fallbackCopy();
                         }
                     },
                     fallbackCopy() {
                         let textArea = document.createElement('textarea');
                         textArea.value = this.uri;
                         textArea.style.position = 'fixed';
                         document.body.appendChild(textArea);
                         textArea.focus();
                         textArea.select();
                         try {
                             document.execCommand('copy');
                             this.triggerSuccess();
                         } catch (err) {
                             alert('Error al copiar al portapapeeles');
                         }
                         document.body.removeChild(textArea);
                     },
                     triggerSuccess() {
                         this.copied = true;
                         setTimeout(() => this.copied = false, 2500);
                     }
                 }">

                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2 border-gray-200 dark:border-zinc-700">
                    <i class="fas fa-link text-purple-500 mr-2"></i> URI de Transmisión
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Copia esta URI para configurarla directamente en OBS, VLC, vMix u otros codificadores compatibles:
                </p>

                <!-- Recuadro de la URI -->
                <div class="p-3 bg-zinc-900 text-green-400 rounded-lg font-mono text-xs break-all border border-zinc-700 select-all">
                    {{ $uri }}
                </div>

                <!-- Botón Interactivo de Copiado con Fallback -->
                <button @click="copyToClipboard()"
                    :class="copied ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                    class="w-full px-4 py-2 text-white font-semibold rounded-lg text-xs transition flex items-center justify-center gap-2 cursor-pointer shadow">
                    <i :class="copied ? 'fas fa-check text-white' : 'fas fa-copy'"></i>
                    <span x-text="copied ? '¡SRT copiado al portapapeeles!' : 'Copiar URI de Conexión'"></span>
                </button>
            </div>
        </div>
    </div>
</x-layouts::app>
