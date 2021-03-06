<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIzpitTable extends Migration {

	public function up()
	{
        Schema::create('izpit', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('id_izpitnega_roka')->nullable();
            $table->decimal('sifra_predmeta', 5, 0)->index('FK_izpit_sifra_predmeta');
            $table->decimal('sifra_studijskega_programa', 7, 0)->index('FK_izpit_sifra_studijskega_programa');
            $table->decimal('sifra_letnika', 1, 0)->index('FK_izpit_sifra_letnika');
            $table->decimal('sifra_studijskega_leta', 2, 0)->index('FK_izpit_sifra_studijskega_leta');
            $table->decimal('sifra_profesorja', 5, 0)->nullable()->index('FK_izpit_sifra_profesorja');
            $table->decimal('vpisna_stevilka', 8, 0)->index('FK_izpit_vpisna_stevilka');
            $table->date('datum');
            $table->integer('ocena')->nullable();
            $table->decimal('tocke_izpita')->nullable();
            $table->decimal('tocke_kolokvijev')->nullable();
            $table->decimal('tocke_vaj')->nullable();
            $table->decimal('tocke_ustni')->nullable();
            $table->time('cas_odjave')->nullable();
            $table->string('email_odjavitelja')->nullable();
        });
	}

	public function down()
	{
        Schema::drop('izpit');
	}

}
