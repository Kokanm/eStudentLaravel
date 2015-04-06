<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLetnikTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letnik', function(Blueprint $table)
		{
			$table->decimal('sifra_letnika', 1, 0)->primary();
			$table->decimal('stevilka_letnika', 1, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('letnik');
	}

}
