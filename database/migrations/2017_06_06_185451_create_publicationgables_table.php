<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicationgables', function (Blueprint $table) {
            $table->integer('publication_id')->unsigned()->index();
            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
            $table->integer('publicationgables_id');
            $table->string('publicationgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('publicationgables');
    }
}
