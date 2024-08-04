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
        Schema::create('favorite', function (Blueprint $table) {
            $table->increments('id_favorite');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_pengguna')->references('id')->on('data_user');
            $table->foreign('id_properti')->references('id')->on('property');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite');
    }
};
