<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Izvedba_predmeta extends Model {

    protected $table = 'izvedba_predmeta';
    protected $primaryKey = array('sifra_predmeta','sifra_studijskega_programa','sifra_letnika','sifra_studijskega_leta', 'sifra_profesorja');
    public $timestamps = false;
    protected $fillable = ['sifra_profesorja', 'sifra_profesorja2', 'sifra_profesorja3'];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
