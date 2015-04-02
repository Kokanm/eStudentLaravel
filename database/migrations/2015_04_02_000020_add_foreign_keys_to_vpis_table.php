<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToVpisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vpis', function(Blueprint $table)
		{
			$table->foreign('vpisna_stevilka', 'FK_Relationship_4')->references('vpisna_stevilka')->on('student')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_vrste_vpisa', 'FK_Relationship_16')->references('sifra_vrste_vpisa')->on('vrsta_vpisa')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_nacina_studija', 'FK_Relationship_17')->references('sifra_nacina_studija')->on('nacin_studija')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_letnika', 'FK_Relationship_18')->references('sifra_letnika')->on('letnik')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_studijskega_programa', 'FK_Relationship_19')->references('sifra_studijskega_programa')->on('studijski_program')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_oblike_studija', 'FK_Relationship_23')->references('sifra_oblike_studija')->on('oblika_studija')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_studijskega_leta', 'FK_Relationship_27')->references('sifra_studijskega_leta')->on('studijsko_leto')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vpis', function(Blueprint $table)
		{
			$table->dropForeign('FK_Relationship_4');
			$table->dropForeign('FK_Relationship_16');
			$table->dropForeign('FK_Relationship_17');
			$table->dropForeign('FK_Relationship_18');
			$table->dropForeign('FK_Relationship_19');
			$table->dropForeign('FK_Relationship_23');
			$table->dropForeign('FK_Relationship_27');
		});
	}

}
