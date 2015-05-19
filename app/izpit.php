<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Izpit extends Model {

    protected $table = 'izpit';
    public $timestamps = false;
    protected $fillable = ['email_odjavitelja, cas_odjave, datum'];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
