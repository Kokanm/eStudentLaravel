<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Studijski_program extends Model {

    protected $table = 'studijski_program';
    protected $primaryKey = 'sifra_studijskega_programa';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
