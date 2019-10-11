<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubegablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtubegables', function (Blueprint $table) {
            $table->integer('youtube_id')->unsigned()->index();
            $table->foreign('youtube_id')->references('id')->on('youtubes')->onDelete('cascade');
            $table->integer('youtubegables_id');
            $table->string('youtubegables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('youtubegables');
    }
}
