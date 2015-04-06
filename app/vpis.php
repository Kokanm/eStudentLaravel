<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:30
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class vpis extends Model {
    protected $table = 'vpis';
    protected $primaryKey = array('vpisna_stevilka','sifra_studijskega_leta');
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];

}