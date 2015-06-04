<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Izpit extends Model {

    protected $table = 'izpit';
    public $timestamps = false;
    protected $fillable = ['email_odjavitelja, cas_odjave, datum', 'vpisna_stevilka', 'id_izpitnega_roka', 'sifra_predmeta', 'sifra_studijskega_programa', 'sifra_letnika', 'sifra_studijskega_leta', 'sifra_profesorja', 'datum'];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
