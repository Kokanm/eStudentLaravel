<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToSklepTable extends Migration {

	public function up()
	{
        Schema::table('vpisan_predmet', function(Blueprint $table)
        {
            $table->foreign('vpisna_stevilka', 'FK_sklep_student')->references('vpisna_stevilka')->on('student')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
	}

	public function down()
	{
        Schema::table('vpisan_predmet', function(Blueprint $table) {
            $table->dropForeign('FK_sklep_student');
        });
    }

}
