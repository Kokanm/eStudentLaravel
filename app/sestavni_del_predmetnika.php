<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Sestavni_del_predmetnika extends Model {

    protected $table = 'sestavni_del_predmetnika';
    protected $primaryKey = 'sifra_sestavnega_dela';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
