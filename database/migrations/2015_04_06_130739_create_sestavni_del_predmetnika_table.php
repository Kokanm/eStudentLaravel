<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSestavniDelPredmetnikaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sestavni_del_predmetnika', function(Blueprint $table)
		{
			$table->decimal('sifra_sestavnega_dela', 2, 0)->primary();
			$table->string('opis_sestavnega_dela', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sestavni_del_predmetnika');
	}

}
