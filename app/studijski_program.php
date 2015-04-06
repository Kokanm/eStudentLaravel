<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:49
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class studijski_program  extends Model {
    protected $table = 'studijski_program';
    protected $primaryKey = 'sifra_studijskega_programa';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
