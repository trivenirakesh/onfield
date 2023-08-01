<?php

namespace App\Http\Middleware;

use App\Models\Entitymst;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user != null) {
            if ($user->entity_type != Entitymst::ENTITYADMIN) {
                return redirect('/');
            }
        }
        return $next($request);
    }
}
