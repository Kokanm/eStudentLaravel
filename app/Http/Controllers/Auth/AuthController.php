<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use \App\Ip_tabela;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuthController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $ip_naslov = \Illuminate\Support\Facades\Request::getClientIp();	// dobi ip naslov
        $ip_je_v_bazi = Ip_tabela::where('ip_naslov', $ip_naslov)->first();	// poglej če je ip naslov že v bazi v tabeli ip_tabela

        if ($this->auth->attempt($credentials, $request->has('remember')))	// če vnesemo pravilno uporabniško ime in geslo
        {
            if ($ip_je_v_bazi == null) {									// če ip naslova še ni v bazi, ga dodaj, nastavi števec napačnih poskusov na 0 in se vpiši
                $ip_tabela = new Ip_tabela;
                $ip_tabela->ip_naslov = $ip_naslov;
                $ip_tabela->stevec = 0;
                $ip_tabela->save();
                return redirect()->intended($this->redirectPath());
            } elseif ($ip_je_v_bazi->stevec < 3) {							// če je ip naslov v bazi, števec napačnih poskusov pa je manjši od 3, resetiraj števec in se vpiši
                $ip_je_v_bazi->stevec = 0;
                $ip_je_v_bazi->save();
                return redirect()->intended($this->redirectPath());
            } elseif ($ip_je_v_bazi->stevec >= 3) {							// če je ip naslov v bazi, števec napačnih poskusov pa je večji ali enak 3
                if($ip_je_v_bazi->updated_at < (new Carbon('-2 minutes'))) {// če sta minili več kot 2 minuti od zadnjega neveljavnega poskusa, resetiraj števec in se vpiši
                    $ip_je_v_bazi->stevec = 0;
                    $ip_je_v_bazi->save();
                    return redirect()->intended($this->redirectPath());
                } else {													// drugače izpiši opozorilo
                    $this->auth->logout();
                    return redirect($this->loginPath())
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors(['Vpis z vašega IP naslova je blokiran za 2 minuti zaradi 3 neuspelih poskusov.']);
                }
            }
        }

        // če vnesemo napačno uporabniško ime ali geslo

        if ($ip_je_v_bazi == null) {										// če ip naslova še ni v bazi, ga dodaj in nastavi števec napačnih poskusov na 1
            $ip_tabela = new Ip_tabela;
            $ip_tabela->ip_naslov = $ip_naslov;
            $ip_tabela->stevec = 1;
            $ip_tabela->save();
        } else {															// če je ip naslov v bazi, povečaj števec napačnih poskusov
            $ip_je_v_bazi->stevec = ($ip_je_v_bazi->stevec) + 1;
            $ip_je_v_bazi->save();
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['Vnesli ste napačno uporabniško ime ali geslo.']);
    }
}
