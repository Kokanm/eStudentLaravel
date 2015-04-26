<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vpis extends Model {

    protected $table = 'vpis';
    protected $primaryKey = 'vpisna_stevilka';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
