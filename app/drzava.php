<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Drzava extends Model {

    protected $table = 'drzava';
    protected $primaryKey = 'sifra_drzava';
    public $timestamps = false;
    protected $fillable = ['naziv_drzave', 'sifra_drzave'];
    protected $guarded = ['*'];
    protected $hidden = [''];
}
