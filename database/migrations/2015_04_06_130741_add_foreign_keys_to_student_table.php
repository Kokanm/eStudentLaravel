<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStudentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student', function(Blueprint $table)
		{
			$table->foreign('postna_stevilka_zacasno', 'FK_posta_zacasno')->references('postna_stevilka')->on('posta')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_drzave_rojstva', 'FK_drzava_rojstva')->references('sifra_drzave')->on('drzava')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_drzave_stalno', 'FK_drzava_stalno')->references('sifra_drzave')->on('drzava')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_drzave_zacasno', 'FK_drzava_zacasno')->references('sifra_drzave')->on('drzava')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_drzave_drzavljanstva', 'FK_drzavljanstvo')->references('sifra_drzave')->on('drzava')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_obcine_stalno', 'FK_obcina_stalno')->references('sifra_obcine')->on('obcina')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_obcine_zacasno', 'FK_obcina_zacasno')->references('sifra_obcine')->on('obcina')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('postna_stevilka_stalno', 'FK_posta_stalno')->references('postna_stevilka')->on('posta')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('email_kandidata', 'FK_Relationship_29')->references('email_kandidata')->on('kandidat')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		    $table->foreign('kraj_rojstva', 'FK_kraj_rojstva')->references('kraj_rojstva')->on('obcina')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student', function(Blueprint $table)
		{
			$table->dropForeign('FK_posta_zacasno');
			$table->dropForeign('FK_drzava_rojstva');
			$table->dropForeign('FK_drzava_stalno');
			$table->dropForeign('FK_drzava_zacasno');
			$table->dropForeign('FK_drzavljanstvo');
			$table->dropForeign('FK_obcina_stalno');
			$table->dropForeign('FK_obcina_zacasno');
			$table->dropForeign('FK_posta_stalno');
			$table->dropForeign('FK_Relationship_29');
            $table->dropForeign('FK_kraj_rojstva');
		});
	}

}
