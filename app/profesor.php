<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:45
 */

namespace App;
use Illuminate\Database\Eloquent\Model;v


class profesor  extends Model {
    protected $table = 'profesor';
    protected $primaryKey = 'sifra_profesorja';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
