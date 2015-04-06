<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:42
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class studijsko_leto  extends Model {
    protected $table = 'studijsko_let';
    protected $primaryKey = 'sifra_studijskega_leta';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}