<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleriesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('galleries', function(Blueprint $table) {
            $table->increments('id');
			$table->string('title');
			$table->string('alias');
			$table->string('regex_tag');
			$table->text('params');
			$table->text('collection')->nullable();
			$table->longText('serialize')->nullable();
			$table->string('status')->default(0);
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
		Schema::drop('galleries');
	}

}
