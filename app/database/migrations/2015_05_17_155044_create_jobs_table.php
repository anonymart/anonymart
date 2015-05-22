<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs',function($table){
			$table->increments('id');
			$table->string('name');
			$table->boolean('is_failed')->default(false);
			$table->string('message')->nullable();
			$table->timestamps();
			$table->timestamp('completed_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobs');
	}

}
