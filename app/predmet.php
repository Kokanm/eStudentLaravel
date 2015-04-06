<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:54
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class predmet  extends Model {
    protected $table = 'predmet';
    protected $primaryKey = 'sifra_predmetaa';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
