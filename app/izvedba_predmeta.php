<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Izvedba_predmeta extends Model {

    protected $table = 'izvedba_predmeta';
    protected $primaryKey =array('sifra_predmeta','sifra_studijskega_programa','sifra_letnika','sifra_studijskega_leta');
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
