<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contents', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('languages');
			$table->string('title', 500);
			$table->string('alias', 500);
			$table->string('image',255)->nullable();
			$table->string('image_path',255)->nullable();
			$table->string('disk',25)->nullable();
			$table->text('intro');
			$table->text('content');
			$table->string('author',255)->nullable();
			$table->enum('type', ['internal', 'external'])->default('internal');
			$table->string('url')->nullable();
			$table->string('suffix',10)->nullable();
			$table->text('params')->nullable();
			$table->text('meta_description');
			$table->text('meta_keywords');
			$table->tinyInteger('status')->default(0);
			$table->tinyInteger('archived')->default(0);
			$table->dateTime('published_at')->nullable();
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
		Schema::drop('contents');
	}

}
