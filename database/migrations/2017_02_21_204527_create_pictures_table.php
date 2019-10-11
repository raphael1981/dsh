<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pictures', function(Blueprint $table) {
            $table->increments('id');
			$table->string('image_name',255);
			$table->string('image_path',255);
			$table->string('disk',25);
			$table->text('translations');
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
		Schema::drop('pictures');
	}

}
