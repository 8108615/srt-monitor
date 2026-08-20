<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrtStream extends Model
{
    use HasFactory;

    protected $table = 'srt_streams';

    protected $fillable = [
        'nombre',
        'modo',
        'url_srt',
        'puerto',
        'latencia',
        'usar_passphrase',
        'passphrase',
        'estado',
        'perdida_paquetes',
        'tiene_audio',
        'tiene_video',
    ];
}
