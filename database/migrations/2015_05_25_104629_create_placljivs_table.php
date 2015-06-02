<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacljivsTable extends Migration {

	public function up()
	{
		Schema::create('placljivs', function(Blueprint $table)
		{
            $table->decimal('vpisna_stevilka', 8, 0);
            $table->decimal('sifra_predmeta', 5, 0);
            $table->smallInteger('tip');
            $table->boolean('placeno');

            $table->foreign('vpisna_stevilka')->references('vpisna_stevilka')->on('student')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sifra_predmeta')->references('sifra_predmeta')->on('predmet')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['vpisna_stevilka', 'sifra_predmeta'], 'placljivs_primary');

        });
	}

	public function down()
	{
		Schema::drop('placljivs');
	}

}
