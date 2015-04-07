<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Oblika_studija extends Model {

    protected $table = 'oblika_studija';
    protected $primaryKey = 'sifra_oblike_studija';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
