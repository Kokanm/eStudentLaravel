<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Zeton extends Model {

    protected $table = 'zeton';
    public $timestamps = false;
    //protected $fillable = ['vpisna_stevilka', 'sifra_studijskega_leta', 'sifra_letnika', 'sifra_oblike_studija', 'sifra_nacina_studija', 'sifra_vrste_vpisa', 'zeton_porabljen', 'prosta_izbira_predmetov'];
    protected $fillable = ['vpisna_stevilka', 'sifra_studijskega_leta', 'sifra_letnika', 'sifra_oblike_studija', 'sifra_nacina_studija', 'sifra_vrste_vpisa', 'zeton_porabljen', 'prosta_izbira_predmetov', 'sifra_studijskega_programa'];
    protected $guarded = ['*'];
    protected $hidden = [''];

}
