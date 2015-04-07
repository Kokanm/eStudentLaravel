<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Obcina extends Model {

    protected $table = 'obcina';
    protected $primaryKey = 'sifra_obcine';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];
}
