<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkgables', function (Blueprint $table) {
            $table->integer('link_id')->unsigned()->index();
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
            $table->integer('linkgables_id');
            $table->string('linkgables_type');
            $table->integer('ord')->default(0);
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('linkgables');
    }
}
