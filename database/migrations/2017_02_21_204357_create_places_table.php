<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('places', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name',255);
			$table->string('alias',255);
			$table->string('image',255)->nullable();
			$table->string('image_path',255)->nullable();
			$table->string('disk',25)->nullable();
			$table->text('description');
			$table->double('lat', 15, 8)->nullable();
			$table->double('lng', 15, 8)->nullable();
			$table->text('params');
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
		Schema::drop('places');
	}

}
