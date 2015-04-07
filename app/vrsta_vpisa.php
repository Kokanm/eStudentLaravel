<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vrsta_vpisa extends Model {

    protected $table = 'vrsta_vpisa';
    protected $primaryKey = 'sifra_vrste_vpisa';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
