<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Studijsko_leto extends Model {

    protected $table = 'studijsko_leto';
    protected $primaryKey = 'sifra_studijskega_leta';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
