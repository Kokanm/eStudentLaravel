<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVpisanPredmetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vpisan_predmet', function(Blueprint $table)
		{
			$table->decimal('vpisna_stevilka', 8, 0);
			$table->decimal('sifra_studijskega_leta_vpisa', 2, 0);
			$table->decimal('sifra_predmeta', 5, 0);
			$table->decimal('sifra_studijskega_programa', 7, 0);
			$table->decimal('sifra_letnika', 1, 0);
			$table->decimal('sifra_studijskega_leta_izvedbe_predmeta', 2, 0);
			$table->primary(array('sifra_predmeta','sifra_studijskega_programa','sifra_letnika','vpisna_stevilka','sifra_studijskega_leta_vpisa','sifra_studijskega_leta_izvedbe_predmeta'),'PK_vpisan_predmet');
			$table->index(['vpisna_stevilka','sifra_studijskega_leta_vpisa'], 'FK_Relationship_24');
			$table->index(['sifra_predmeta','sifra_studijskega_programa','sifra_letnika','sifra_studijskega_leta_izvedbe_predmeta'], 'FK_Relationship_25');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vpisan_predmet');
	}

}
