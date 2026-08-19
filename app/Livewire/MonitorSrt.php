<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SrtStream;

class MonitorSrt extends Component
{
    public function render()
    {
        // Consultamos todos los streams de la tabla srt_streams
        $streams = SrtStream::all();

        // Retornamos la vista que está en resources/views/livewire/senal/monitor-srt.blade.php
        return view('livewire.senal.monitor-srt', [
            'streams' => $streams
        ]);
    }
}
