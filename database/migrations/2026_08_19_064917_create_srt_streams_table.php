<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('srt_streams', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                  // Ej: "Transmisión Principal Estadio"
            $table->string('url_srt');                 // Ej: "192.168.1.50" o "thegameftp.com"
            $table->integer('puerto')->default(9000);  // Puerto SRT (ej: 9000)
            $table->enum('modo', ['listener', 'caller'])->default('listener'); // Modo de conexión SRT
            $table->string('estado')->default('OFFLINE'); // ONLINE, OFFLINE, WARNING
            $table->float('perdida_paquetes')->default(0);
            $table->boolean('tiene_audio')->default(true);
            $table->boolean('tiene_video')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srt_streams');
    }
};
