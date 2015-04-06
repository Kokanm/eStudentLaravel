<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:19
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class drzava extends Model {
    protected $table = 'drzava';
    protected $primaryKey = 'sifra_drzava';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

} 