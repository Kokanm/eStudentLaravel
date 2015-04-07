<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model {

    protected $table = 'kandidat';
    protected $primaryKey = 'uporabnisko_ime_kandidata';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];
}
