<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:51
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class oblika_studija  extends Model {
    protected $table = 'oblika_studija';
    protected $primaryKey = 'sifra_oblike_studija';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
