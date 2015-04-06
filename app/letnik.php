<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:48
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class letnik  extends Model {
    protected $table = 'letnik';
    protected $primaryKey = 'sifra_letnika';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
