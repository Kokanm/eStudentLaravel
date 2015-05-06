<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKandidatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kandidat', function(Blueprint $table)
		{
			$table->decimal('sifra_studijskega_programa', 7, 0)->index('FK_Relationship_30');
			$table->decimal('vpisna_stevilka', 8, 0)->nullable()->index('FK_Relationship_28');
			$table->string('ime_kandidata', 30);
			$table->string('priimek_kandidata', 30);
			$table->string('email_kandidata', 60)->primary();
            $table->boolean('zeton');
            $table->boolean('zeton_porabljen');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kandidat');
	}

}
