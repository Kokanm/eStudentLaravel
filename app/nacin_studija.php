<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:39
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class nacin_studija  extends Model {
    protected $table = 'nacin_studija';
    protected $primaryKey = 'sifra_nacina_studija';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];

}