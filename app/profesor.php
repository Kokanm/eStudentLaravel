<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model {

    protected $table = 'profesor';
    protected $primaryKey = 'sifra_profesorja';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
