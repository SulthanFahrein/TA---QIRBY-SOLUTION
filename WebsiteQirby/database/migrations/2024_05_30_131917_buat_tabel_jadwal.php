<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->increments('id_jadwal');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_properti');
            $table->date('tanggal');
            $table->time('pukul');
            $table->string('pic')->nullable();
            $table->tinyText('catatan')->nullable();
            $table->enum('jadwal_diterima', ['accept', 'pending', 'reject', 'done'])->default('pending');
            $table->foreign('id_pengguna')->references('id')->on('data_user');
            $table->foreign('id_properti')->references('id')->on('property');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
