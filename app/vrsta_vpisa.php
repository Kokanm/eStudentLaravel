<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:36
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class vrsta_vpisa extends Model {
    protected $table = 'vrsta_vpisa';
    protected $primaryKey = 'sifra_vrste_vpisa';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}