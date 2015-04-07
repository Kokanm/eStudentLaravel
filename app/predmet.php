<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Predmet extends Model {

    protected $table = 'predmet';
    protected $primaryKey = 'sifra_predmetaa';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];
}
