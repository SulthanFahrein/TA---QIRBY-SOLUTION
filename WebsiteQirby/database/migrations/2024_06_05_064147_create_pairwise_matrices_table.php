<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePairwiseMatricesTable extends Migration
{
    public function up()
    {
        Schema::create('pairwise_matrices', function (Blueprint $table) {
            $table->id();
            $table->string('criterion_name');
            $table->json('matrix'); // Simpan matriks dalam format JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pairwise_matrices');
    }
}