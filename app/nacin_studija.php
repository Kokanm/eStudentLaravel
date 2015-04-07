<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Nacin_studija extends Model {

    protected $table = 'nacin_studija';
    protected $primaryKey = 'sifra_nacina_studija';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];
}
