<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPredmetStudijskegaProgramaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('predmet_studijskega_programa', function(Blueprint $table)
		{
			$table->foreign('sifra_letnika', 'FK_Relationship_13')->references('sifra_letnika')->on('letnik')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_predmeta', 'FK_Relationship_10')->references('sifra_predmeta')->on('predmet')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_studijskega_programa', 'FK_Relationship_11')->references('sifra_studijskega_programa')->on('studijski_program')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_sestavnega_dela', 'FK_Relationship_12')->references('sifra_sestavnega_dela')->on('sestavni_del_predmetnika')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('predmet_studijskega_programa', function(Blueprint $table)
		{
			$table->dropForeign('FK_Relationship_13');
			$table->dropForeign('FK_Relationship_10');
			$table->dropForeign('FK_Relationship_11');
			$table->dropForeign('FK_Relationship_12');
		});
	}

}
