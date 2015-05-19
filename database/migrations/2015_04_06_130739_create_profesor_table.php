<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfesorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profesor', function(Blueprint $table)
		{
			$table->decimal('sifra_profesorja', 5, 0)->primary();
			$table->string('ime_profesorja', 30);
			$table->string('priimek_profesorja', 30);
            $table->string('email_profesorja', 50)->unique();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('profesor');
	}

}
