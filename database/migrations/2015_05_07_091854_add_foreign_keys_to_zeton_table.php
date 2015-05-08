<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToZetonTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zeton', function(Blueprint $table)
        {
            $table->foreign('vpisna_stevilka', 'FK_Relationship_31')->references('vpisna_stevilka')->on('student')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sifra_studijskega_leta', 'FK_Relationship_34')->references('sifra_studijskega_leta')->on('studijsko_leto')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sifra_letnika', 'FK_Relationship_35')->references('sifra_letnika')->on('letnik')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sifra_oblike_studija', 'FK_Relationship_36')->references('sifra_oblike_studija')->on('oblika_studija')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sifra_nacina_studija', 'FK_Relationship_37')->references('sifra_nacina_studija')->on('nacin_studija')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sifra_vrste_vpisa', 'FK_Relationship_38')->references('sifra_vrste_vpisa')->on('vrsta_vpisa')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sifra_studijskega_programa', 'FK_Relationship_39')->references('sifra_studijskega_programa')->on('studijski_program')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zeton', function(Blueprint $table)
        {
            $table->dropForeign('FK_Relationship_31');
            $table->dropForeign('FK_Relationship_34');
            $table->dropForeign('FK_Relationship_35');
            $table->dropForeign('FK_Relationship_36');
            $table->dropForeign('FK_Relationship_37');
            $table->dropForeign('FK_Relationship_38');
            $table->dropForeign('FK_Relationship_39');
        });
    }

}
