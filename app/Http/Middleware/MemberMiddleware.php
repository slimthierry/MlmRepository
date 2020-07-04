<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if (!Sentinel::check()) {
        //     return redirect()->route('login', ['lang' => App::getLocale()])->with('flashMessage', [
        //         'class'  =>  'warning',
        //         'message'   =>  Lang::get('error.login')
        //     ]);
        // }
        // $user = \Sentinel::getUser();
        // $permissions = $user->permissions;
        // if (!isset($permissions['member'])) {
        //     return redirect()->route('login', ['lang' => \App::getLocale()])->with('flashMessage', [
        //         'class'  =>  'warning',
        //         'message'   =>  \Lang::get('error.login')
        //     ]);
        // }
        // if ($permissions['member'] != 1) {
        //     return redirect()->route('login', ['lang' => \App::getLocale()])->with('flashMessage', [
        //         'class'  =>  'warning',
        //         'message'   =>  \Lang::get('error.login')
        //     ]);
        // }

        return $next($request);
    }
}
