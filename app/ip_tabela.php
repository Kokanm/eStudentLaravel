<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip_tabela extends Model {

    protected $table = 'ip_tabela';

    protected $fillable = ['ip_naslov', 'stevec'];
}
