<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medias', function(Blueprint $table) {
            $table->increments('id');
			$table->string('title',255)->nullable();
			$table->string('filename',255);
			$table->string('full_filename',300);
			$table->string('disk',25);
			$table->string('media_relative_path', 255);
			$table->string('mimetype');
			$table->string('suffix');
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
		Schema::drop('medias');
	}

}
