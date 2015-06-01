<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Placljivs extends Model {

    protected $table = 'placljivs';
    protected $primaryKey = 'vpisna_stevilka';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}