<x-layouts::app title="Registrar Nuevo Stream SRT">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Registrar Nuevo Stream SRT</flux:heading>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresa los parámetros de red, modo y seguridad para configurar el enlace SRT.</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-3xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-md"
         x-data="{ modo: '{{ old('modo', 'listener') }}', usarPass: {{ old('usar_passphrase') ? 'true' : 'false' }} }">

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

            <!-- Modo de Conexión (Ubicado justo abajo del nombre) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modo de Conexión SRT</label>
                <select name="modo" x-model="modo" class="w-full bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="listener">Listener (El servidor espera la llamada del emisor)</option>
                    <option value="caller">Caller (El servidor inicia la llamada hacia el equipo)</option>
                </select>
                @error('modo')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Dirección IP o URL (Solo aparece si el modo es CALLER) -->
                <div x-show="modo === 'caller'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección IP / Host</label>
                    <flux:input name="url_srt" type="text" placeholder="Ej. 192.168.1.50 o thegameftp.com" value="{{ old('url_srt') }}" />
                    @error('url_srt')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Puerto (Siempre visible para ambos modos) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Puerto SRT</label>
                    <flux:input name="puerto" type="number" placeholder="Ej. 9000" value="{{ old('puerto', 9000) }}" required />
                    @error('puerto')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Latencia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Latencia (ms)</label>
                    <flux:input name="latencia" type="number" placeholder="Ej. 200" value="{{ old('latencia', 200) }}" required />
                    <span class="text-xs text-gray-400 mt-1 block">Buffer de retransmisión recomendado en milisegundos.</span>
                    @error('latencia')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Passphrase / Contraseña con Checkbox -->
            <div class="p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg border border-gray-200 dark:border-zinc-700 space-y-3">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="usar_passphrase" id="usar_passphrase" value="1" x-model="usarPass" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                    <label for="usar_passphrase" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                        Proteger enlace con Passphrase (Contraseña)
                    </label>
                </div>

                <div x-show="usarPass" x-transition>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña (Passphrase)</label>
                    <flux:input name="passphrase" type="text" placeholder="Ingresa la contraseña de encriptación SRT" value="{{ old('passphrase') }}" />
                    @error('passphrase')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
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
