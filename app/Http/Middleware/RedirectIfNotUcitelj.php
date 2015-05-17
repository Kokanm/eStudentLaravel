<?php namespace App\Http\Middleware;

use Closure;

class RedirectIfNotUcitelj {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::guest()) {
            return redirect('home');
        }
        if(! $request->user()->isProfesor()) {
            return redirect('home');
        }
        return $next($request);
    }

}
