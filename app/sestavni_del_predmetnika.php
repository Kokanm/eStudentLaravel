<?php
/**
 * Created by PhpStorm.
 * User: Bojan
 * Date: 06/04/2015
 * Time: 16:47
 */

namespace App;
use Illuminate\Database\Eloquent\Model;


class sestavni_del_predmetnika  extends Model {
    protected $table = 'sestavni_del_predmetnika';
    protected $primaryKey = 'sifra_sestavnega_dela';
    public $timestamps = false;
    protected $fillable = [''];
    protected $guarded = ['*'];
    protected $hidden = [''];


}
