<div class="p-6 bg-gray-800 rounded-lg shadow-xl">
    <h2 class="text-2xl font-semibold text-white mb-6">Monitoreo de Streams SRT</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs uppercase bg-gray-700 text-gray-100">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Dirección (IP:Puerto)</th>
                    <th class="px-6 py-3">Modo</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Pérdida Pkts</th>
                </tr>
            </thead>
            <tbody>
                @forelse($streams as $stream)
                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-white">{{ $stream->nombre }}</td>
                    <td class="px-6 py-4">{{ $stream->url_srt }}:{{ $stream->puerto }}</td>
                    <td class="px-6 py-4 capitalize">{{ $stream->modo }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            {{ $stream->estado == 'ONLINE' ? 'bg-green-600 text-white' :
                               ($stream->estado == 'WARNING' ? 'bg-yellow-500 text-black' : 'bg-red-600 text-white') }}">
                            {{ $stream->estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $stream->perdida_paquetes }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">No hay streams registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
