<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendasTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agendas', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('languages');
			$table->integer('place_id')->unsigned()->nullable();
			$table->foreign('place_id')->references('id')->on('places');
			$table->string('title',255);
			$table->string('alias',255);
			$table->string('image',255)->nullable();
			$table->string('image_path',255)->nullable();
			$table->string('disk',25)->nullable();
			$table->text('intro');
			$table->text('content');
			$table->text('params')->nullable();
			$table->date('begin')->nullable();
			$table->date('end')->nullable();
			$table->time('begin_time')->nullable();
			$table->time('end_time')->nullable();
			$table->string('suffix',10)->nullable();
			$table->text('meta_description');
			$table->text('meta_keywords');
			$table->tinyInteger('status')->default(0);
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agendas');
	}

}
