<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToKandidatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kandidat', function(Blueprint $table)
		{
			$table->foreign('sifra_studijskega_programa', 'FK_Relationship_30')->references('sifra_studijskega_programa')->on('studijski_program')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('vpisna_stevilka', 'FK_Relationship_28')->references('vpisna_stevilka')->on('student')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kandidat', function(Blueprint $table)
		{
			$table->dropForeign('FK_Relationship_30');
			$table->dropForeign('FK_Relationship_28');
		});
	}

}
