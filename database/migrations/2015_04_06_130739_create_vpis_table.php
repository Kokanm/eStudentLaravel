<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVpisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vpis', function(Blueprint $table)
		{
			$table->decimal('vpisna_stevilka', 8, 0);
			$table->decimal('sifra_studijskega_leta', 2, 0)->index('FK_Relationship_27');
			$table->decimal('sifra_oblike_studija', 1, 0)->index('FK_Relationship_23');
			$table->decimal('sifra_studijskega_programa', 7, 0)->nullable()->index('FK_Relationship_19');
			$table->decimal('sifra_nacina_studija', 1, 0)->index('FK_Relationship_17');
            $table->decimal('sifra_vrste_studija', 5, 0)->index('FK_Vrste_Studija');
			$table->decimal('sifra_vrste_vpisa', 2, 0)->index('FK_Relationship_16');
			$table->decimal('sifra_letnika', 1, 0)->index('FK_Relationship_18');
            $table->string('zavod', 30)->nullable();
            $table->string('kraj_izvajanja', 30)->nullable();
			$table->boolean('vpis_potrjen');
			$table->primary(array('vpisna_stevilka','sifra_studijskega_leta'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vpis');
	}

}
