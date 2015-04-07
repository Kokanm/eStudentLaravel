<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student', function(Blueprint $table)
		{
			$table->decimal('vpisna_stevilka', 8, 0)->primary();
			$table->string('uporabnisko_ime_kandidata', 30)->nullable()->index('FK_Relationship_29');
			$table->string('ime_studenta', 30);
			$table->string('priimek_studenta', 30);
			$table->string('naslov_stalno', 30);
			$table->decimal('postna_stevilka_stalno', 4, 0)->index('FK_posta_stalno');
			$table->decimal('sifra_obcine_stalno', 3, 0)->index('FK_obcina_stalno');
			$table->decimal('sifra_drzave_stalno', 3, 0)->index('FK_drzava_stalno');
			$table->string('naslov_zacasno', 30)->nullable();
            $table->string('naslov_vrocanja', 30)->nullable();
			$table->decimal('postna_stevilka_zacasno', 4, 0)->nullable()->index('FK_posta_zacasno');
			$table->decimal('sifra_obcine_zacasno', 3, 0)->nullable()->index('FK_obcina_zacasno');
			$table->decimal('sifra_drzave_zacasno', 3, 0)->nullable()->index('FK_drzava_zacasno');
			$table->decimal('davcna_stevilka', 8, 0)->nullable();
			$table->decimal('emso', 13, 0)->nullable();
			$table->date('datum_rojstva');
            $table->string('obcina_rojstva', 30)->nullable();
			$table->string('kraj_rojstva', 30);
			$table->decimal('sifra_drzave_rojstva', 3, 0)->index('FK_drzava_rojstva');
			$table->decimal('sifra_drzave_drzavljanstva', 3, 0)->index('FK_drzavljanstvo');
			$table->string('spol', 1);
			$table->string('prenosni_telefon', 15)->nullable();
			$table->string('email_studenta', 60);
			$table->string('geslo_studenta', 100);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student');
	}

}
