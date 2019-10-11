<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contentgables', function (Blueprint $table) {
            $table->integer('content_id')->unsigned()->index();
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->integer('contentgables_id');
            $table->string('contentgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contentgables');
    }
}
