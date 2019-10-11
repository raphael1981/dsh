<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadscenesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leadscenes', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('languages');
			$table->string('name',100);
			$table->longText('serialize');
			$table->longText('fast_serialize');
			$table->tinyInteger('active')->default(0);
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
		Schema::drop('leadscenes');
	}

}
