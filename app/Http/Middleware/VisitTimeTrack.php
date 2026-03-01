<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class VisitTimeTrack
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('product*')) {
            // dd(Session::all());
            if (! session()->has('start_time')) {
                Session::put('start_time', now());
            }
        } else {
            if (session()->has('start_time')) {
                $duration = now()->diffInSeconds(session()->get('start_time'));
                if (session()->has('visitor_id')) {
                    DB::table('shetabit_visits')->where('id', session()->get('visitor_id'))->update(['duration' => $duration]);
                    session()->forget('visitor_id');
                }
                session()->forget('start_time');
            }
        }

        return $next($request);
    }
}
