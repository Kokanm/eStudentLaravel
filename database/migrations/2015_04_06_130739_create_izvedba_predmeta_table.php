<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIzvedbaPredmetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('izvedba_predmeta', function(Blueprint $table)
		{
			$table->decimal('sifra_predmeta', 5, 0);
			$table->decimal('sifra_studijskega_programa', 7, 0);
			$table->decimal('sifra_letnika', 1, 0);
			$table->decimal('sifra_studijskega_leta', 2, 0)->index('FK_Relationship_26');
			$table->decimal('sifra_profesorja', 5, 0)->index('FK_profesor1');
			$table->decimal('sifra_profesorja2', 5, 0)->nullable()->index('FK_profesor2');
			$table->decimal('sifra_profesorja3', 5, 0)->nullable()->index('FK_profesor3');
			$table->primary(['sifra_predmeta','sifra_studijskega_programa','sifra_letnika','sifra_studijskega_leta', 'sifra_profesorja'],'PK_izvedba_predmeta');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('izvedba_predmeta');
	}

}
