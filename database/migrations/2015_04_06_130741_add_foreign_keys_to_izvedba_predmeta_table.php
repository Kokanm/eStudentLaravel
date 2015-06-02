<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIzvedbaPredmetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('izvedba_predmeta', function(Blueprint $table)
		{
			$table->foreign('sifra_predmeta', 'FK_Relationship_14')->references('sifra_predmeta')->on('predmet_studijskega_programa')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sifra_studijskega_leta', 'FK_Relationship_26')->references('sifra_studijskega_leta')->on('studijsko_leto')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('izvedba_predmeta', function(Blueprint $table)
		{
			$table->dropForeign('FK_Relationship_14');
			$table->dropForeign('FK_Relationship_26');
		});
	}

}
