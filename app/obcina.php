<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:27
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class obcina extends Model {
    protected $table = 'obcina';
    protected $primaryKey = 'sifra_obcine';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

} 