<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Logotypegables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logotypegables', function (Blueprint $table) {
            $table->integer('logotype_id')->unsigned()->index();
            $table->foreign('logotype_id')->references('id')->on('logotypes')->onDelete('cascade');
            $table->integer('logotypegables_id');
            $table->string('logotypegables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logotypegables');
    }
}
