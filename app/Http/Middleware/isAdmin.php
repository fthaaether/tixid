<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->role == 'admin'){
            //jika sudah login & rolenya admin
            // return $next -> boleh akses
            return $next($request); // untuk melanjutkan apakah dia  boleh akses atau ngga nya
        } else {
            //jika blm login/bukan admin
            return redirect()->route('home');
        }
    }
}
