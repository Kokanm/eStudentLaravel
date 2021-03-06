<?php namespace App\Http\Middleware;

use Closure;

class RedirectIfNotReferent {

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
        if(! $request->user()->isReferent()) {
            return redirect('home');
        }
        return $next($request);
    }

}
