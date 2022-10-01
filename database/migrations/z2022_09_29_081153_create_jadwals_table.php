<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_guru");
            $table->unsignedBigInteger('id_mapel');
            $table->unsignedBigInteger('id_semester');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_ruang_kelas'); 
            $table->unsignedBigInteger('id_hari');
            $table->integer('jam_awal');
            $table->integer('jam_akhir');
            
            $table->foreign('id_guru')->references('id')->on('guru');
            $table->foreign('id_mapel')->references('id')->on('mapel');
            $table->foreign('id_semester')->references('id')->on('semester');
            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->foreign('id_ruang_kelas')->references('id')->on('ruang_kelas');
            $table->foreign('id_hari')->references('id')->on('hari');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};
