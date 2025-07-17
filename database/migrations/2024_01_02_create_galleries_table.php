<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();           // Supaya bisa kosong kalau cuma upload gambar
            $table->text('description')->nullable();       // Keterangan opsional
            $table->string('image');                       // Nama file gambar
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('galleries');
    }
};