<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;


class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if(auth()->check() && (auth()->user()->status == 2)){
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Ваш обліковий запис заблоковано, зв’яжіться з адміністратором.');

        }

        return $next($request);


    }
}
