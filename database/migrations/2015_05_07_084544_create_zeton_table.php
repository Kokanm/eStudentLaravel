<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZetonTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zeton', function(Blueprint $table)
        {
            $table->increments('id');
            $table->decimal('vpisna_stevilka', 8, 0);
            $table->decimal('sifra_studijskega_programa', 7,0)->index('FK_Relationship_39');
            $table->decimal('sifra_studijskega_leta', 2, 0)->index('FK_Relationship_34');
            $table->decimal('sifra_letnika', 1, 0)->index('FK_Relationship_35');
            $table->decimal('sifra_oblike_studija', 1, 0)->index('FK_Relationship_36');
            $table->decimal('sifra_nacina_studija', 1, 0)->index('FK_Relationship_37');
            $table->decimal('sifra_vrste_vpisa', 2, 0)->index('FK_Relationship_38');

            $table->boolean('zeton_porabljen');
            $table->boolean('prosta_izbira_predmetov');

            //$table->primary(array('vpisna_stevilka','sifra_studijskega_programa','sifra_studijskega_leta','sifra_letnika'), 'PK_zeton');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zeton');
    }

}
