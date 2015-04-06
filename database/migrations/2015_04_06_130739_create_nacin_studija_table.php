<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNacinStudijaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nacin_studija', function(Blueprint $table)
		{
			$table->decimal('sifra_nacina_studija', 1, 0)->primary();
			$table->string('opis_nacina_studija', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nacin_studija');
	}

}
