<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vpisan_predmet extends Model {

    protected $table = 'vpisan_predmet';
    protected $primaryKey = array('sifra_predmeta','sifra_studijskega_programa','sifra_letnika','vpisna_stevilka','sifra_studijskega_leta','sifra_studijskega_leta_izvedbe_predmeta');
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
