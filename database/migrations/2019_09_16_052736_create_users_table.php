<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('province');
			$table->string('hospcode');
			$table->string('title_name');
			$table->string('title_name_other');
			$table->string('name');
			$table->string('lastname');
			$table->string('email');
			$table->string('phone');
			$table->string('fax');
			$table->string('password');
			$table->enum('status', ['active', 'reject'])->default('active');
			$table->rememberToken();
			$table->timestamps();
			$table->engine = 'InnoDB';
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
