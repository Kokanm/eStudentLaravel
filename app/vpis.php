<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vpis extends Model {

    protected $table = 'vpis';
    protected $primaryKey = array('vpisna_stevilka','sifra_studijskega_programa');
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
