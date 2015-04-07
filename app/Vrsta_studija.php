<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Vrsta_studija extends Model {

    protected $table = 'vrsta_studija';
    protected $primaryKey = 'sifra_vrste_studija';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
