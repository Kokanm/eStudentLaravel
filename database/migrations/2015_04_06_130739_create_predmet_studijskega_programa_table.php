<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePredmetStudijskegaProgramaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('predmet_studijskega_programa', function(Blueprint $table)
		{
			$table->decimal('sifra_predmeta', 5, 0);
			$table->decimal('sifra_studijskega_programa', 7, 0)->index('FK_Relationship_11');
			$table->decimal('sifra_letnika', 1, 0)->index('FK_Relationship_13');
			$table->decimal('sifra_sestavnega_dela', 2, 0)->nullable()->index('FK_Relationship_12');
			$table->primary(array('sifra_predmeta','sifra_studijskega_programa','sifra_letnika'),'PK_predmet_studijskega_programa');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('predmet_studijskega_programa');
	}

}
