<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudijskoLetoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('studijsko_leto', function(Blueprint $table)
		{
			$table->decimal('sifra_studijskega_leta', 2, 0)->primary();
			$table->string('stevilka_studijskega_leta', 9);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('studijsko_leto');
	}

}
