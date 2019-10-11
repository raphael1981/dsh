<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('members', function(Blueprint $table) {
            $table->increments('id');
			$table->string('email',100)->unique();
			$table->string('password')->nullable();
			$table->string('name', 100)->nullable();
			$table->string('surname', 100)->nullable();
			$table->tinyInteger('newsletter')->default(0);
			$table->tinyInteger('status')->default(-1);
			$table->string('verification_token', 255);
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
		Schema::drop('members');
	}

}
