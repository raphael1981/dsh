<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('languages');
			$table->integer('template_id')->unsigned()->nullable();
			$table->foreign('template_id')->references('id')->on('templates');
			$table->integer('ref')->nullable();
			$table->string('title', 100);
			$table->string('alias',100);
			$table->string('path');
			$table->string('link');
			$table->text('description')->nullable();
			$table->text('description_links')->nullable();
			$table->enum('ltype', ['internal', 'external'])->nullable()->default(null);
			$table->text('params');
			$table->integer('ord');
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
		Schema::drop('links');
	}

}
