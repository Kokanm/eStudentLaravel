<?php namespace App\Http\Middleware;

use Closure;

class RedirectIfNotStudRef {

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
        if( $request->user()->isReferent()) {

            return $next($request);
        }
        if( $request->user()->isStudent()) {

            return $next($request);
        }
        return redirect('home');
    }

}
