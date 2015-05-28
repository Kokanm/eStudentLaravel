<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToVpisanPredmetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vpisan_predmet', function(Blueprint $table)
		{
			$table->foreign('sifra_predmeta', 'FK_Relationship_25')->references('sifra_predmeta')->on('izvedba_predmeta')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('vpisna_stevilka', 'FK_Relationship_24')->references('vpisna_stevilka')->on('vpis')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}

	public function down()
	{
		Schema::table('vpisan_predmet', function(Blueprint $table)
		{
			$table->dropForeign('FK_Relationship_25');
			$table->dropForeign('FK_Relationship_24');
		});
	}

}
