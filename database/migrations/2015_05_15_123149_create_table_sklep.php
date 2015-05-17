<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSklep extends Migration {

	public function up()
	{
        Schema::create('sklep', function(Blueprint $table) {
            $table->increments('id');
            $table->decimal('vpisna_stevilka', 8, 0)->index('FK_sklep_student');
            $table->longText('besedilo')->nullable();
            $table->string('izdajatelj');
            $table->date('datum');
            $table->date('veljaven_do');
        });
	}

	public function down()
	{
        Schema::drop('sklep');
	}

}
