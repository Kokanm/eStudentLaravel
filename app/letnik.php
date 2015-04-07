<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Letnik extends Model {

    protected $table = 'letnik';
    protected $primaryKey = 'sifra_letnika';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
