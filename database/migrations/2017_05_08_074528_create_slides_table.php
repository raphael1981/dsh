<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('slides', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('languages');
			$table->string('title',255);
			$table->string('url',255);
			$table->string('image',255);
			$table->text('description');
			$table->string('color',255);
			$table->integer('ord');
			$table->tinyInteger('status');
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
		Schema::drop('slides');
	}

}
