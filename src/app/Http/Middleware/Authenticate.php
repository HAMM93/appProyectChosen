<?php

namespace App\Http\Middleware;

use App\Exceptions\User\UserNotAuthenticateException;
use App\Models\Sessions;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Validator;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @throws \Exception
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $token = $request->header('Auth-Secure-Token');

        $validation = Validator::make(['Auth-Secure-Token' => $token],[
            'Auth-Secure-Token' => 'required|uuid'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $session = Sessions::where('status', 'active')
            ->where('auth_secure_token', $token)
            ->where('expired_at', '>', Carbon::now())
            ->orderByDesc('id')
            ->first();

        if(!$session)
            throw new UserNotAuthenticateException();

        return $next($request);
    }
}
