<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudijskiProgramTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('studijski_program', function(Blueprint $table)
		{
			$table->decimal('sifra_studijskega_programa', 7, 0)->primary();
			$table->string('naziv_studijskega_programa', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('studijski_program');
	}

}
