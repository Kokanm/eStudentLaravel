<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IzpitniRokTable extends Migration {

	public function up()
	{
        Schema::create('izpitni_rok', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('id_izvedbe_predmeta');

            $table->date('datum');
            $table->text('ura')->nullable();
            $table->decimal('sifra_studijskega_programa', 7, 0);
            $table->decimal('sifra_letnika', 1, 0);
            $table->decimal('sifra_studijskega_leta', 2, 0);
            $table->decimal('sifra_profesorja', 5, 0)->nullable();
            $table->decimal('sifra_predmeta', 5, 0);
            $table->text('opombe')->nullable();
            $table->text('predavalnica')->nullable();
        });
	}


	public function down()
	{
        Schema::drop('izpitni_rok');
	}

}
