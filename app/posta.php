<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:08
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class posta extends Model {
    protected $table = 'posta';
    protected $primaryKey = 'postna_stevilka';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}