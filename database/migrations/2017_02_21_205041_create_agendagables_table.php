<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendagablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendagables', function (Blueprint $table) {
            $table->integer('agenda_id')->unsigned()->index();
            $table->foreign('agenda_id')->references('id')->on('agendas')->onDelete('cascade');
            $table->integer('agendagables_id');
            $table->string('agendagables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('agendagables');
    }
}
