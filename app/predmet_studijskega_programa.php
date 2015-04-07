<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Predmet_studijskega_programa extends Model {

    protected $table = 'predmet_studijskega_programa';
    protected $primaryKey = array('sifra_predmeta','sifra_studijskega_programa','sifra_letnika');
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
