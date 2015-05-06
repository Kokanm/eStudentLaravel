<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVrstaStudija extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('vrsta_studija', function(Blueprint $table)
        {
            $table->decimal('sifra_vrste_studija', 5, 0)->primary();
            $table->string('opis_vrste_studija', 80);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vrsta_studija');
	}

}
