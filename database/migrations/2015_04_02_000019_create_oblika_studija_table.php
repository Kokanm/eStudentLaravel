<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOblikaStudijaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oblika_studija', function(Blueprint $table)
		{
			$table->decimal('sifra_oblike_studija', 1, 0)->primary();
			$table->string('opis_oblike_studija', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oblika_studija');
	}

}
