<x-layouts::app title="Registrar Nuevo Stream SRT">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Registrar Nuevo Stream SRT</flux:heading>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresa los parámetros de red para configurar el monitoreo del enlace.</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-3xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-md">
        <form action="{{ route('admin.streams.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nombre del Stream -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Stream / Enlace</label>
                <flux:input name="nombre" type="text" placeholder="Ej. Cámara Principal - Estadio" value="{{ old('nombre') }}" required />
                @error('nombre')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Dirección IP o URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección IP / Host</label>
                    <flux:input name="url_srt" type="text" placeholder="Ej. 192.168.1.50 o thegameftp.com" value="{{ old('url_srt') }}" required />
                    @error('url_srt')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Puerto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Puerto SRT</label>
                    <flux:input name="puerto" type="number" placeholder="Ej. 9000" value="{{ old('puerto', 9000) }}" required />
                    @error('puerto')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Modo de Conexión (Listener / Caller) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modo de Conexión SRT</label>
                <select name="modo" class="w-full bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="listener" {{ old('modo') == 'listener' ? 'selected' : '' }}>Listener (El servidor espera la llamada del emisor)</option>
                    <option value="caller" {{ old('modo') == 'caller' ? 'selected' : '' }}>Caller (El servidor inicia la llamada hacia el equipo)</option>
                </select>
                @error('modo')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <a href="{{ route('admin.streams.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-save mr-1"></i> Guardar Stream
                </button>
            </div>
        </form>
    </div>
</x-layouts::app>
