<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVrstaVpisaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vrsta_vpisa', function(Blueprint $table)
		{
			$table->decimal('sifra_vrste_vpisa', 2, 0)->primary();
			$table->string('opis_vrste_vpisa', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vrsta_vpisa');
	}

}
