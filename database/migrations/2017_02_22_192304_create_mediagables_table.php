<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediagablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediagables', function (Blueprint $table) {
            $table->integer('media_id')->unsigned()->index();
            $table->foreign('media_id')->references('id')->on('medias')->onDelete('cascade');
            $table->integer('mediagables_id');
            $table->string('mediagables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mediagables');
    }
}
