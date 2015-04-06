<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:29
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class kandidat extends Model {
    protected $table = 'kandidat';
    protected $primaryKey = 'uporabnisko_ime_kandidata';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
