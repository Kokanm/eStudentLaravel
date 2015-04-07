<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Posta extends Model {

    protected $table = 'posta';
    protected $primaryKey = 'postna_stevilka';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
