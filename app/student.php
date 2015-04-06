<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 15:47
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class student extends Model {
    protected $table = 'student';
    protected $primaryKey = 'vpisna_stevilka';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

} 