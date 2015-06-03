<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Izpitni_rok extends Model {

    protected $table = 'izpitni_rok';
    public $timestamps = false;
    protected $fillable = ['id_izvedbe_predmeta', 'sifra_studijskega_leta', 'sifra_letnika', 'sifra_studijskega_programa', 'sifra_profesorja', 'sifra_predmeta', 'datum', 'ura', 'opombe', 'predavalnica'];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
