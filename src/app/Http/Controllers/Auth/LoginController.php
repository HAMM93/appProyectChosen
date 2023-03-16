<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Sessions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\If_;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        } else {
            return $this->attempts($request->get('email'), $request->get('password'));
        }
    }

    private function attempts(string $email, string $password)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['errors' => ['login_or_password'=> [trans('auth.invalid-credentials')]]], 401);
        } else {
            if (Hash::check($password, $user->password)) {
                $active_sessions = Sessions::with('user')
                    ->where('user_id', $user->id)
                    ->where('status', 'active')
                    ->get();

                $session = $user->sessions()->create([
                    'expired_at' => Carbon::now()->addMinutes(config('app.session_ttl')),
                    'change_by' => 'login',
                    'auth_secure_token' => Str::uuid()
                ]);

                if ($active_sessions && !empty($active_sessions)) {
                    foreach ($active_sessions as $active_session) {
                        DB::table('sessions')
                            ->where('id', $active_session->id)
                            ->update(['change_by' => 'login', 'disabled_by_session_id' => $session->id, 'status' => 'inactive']);
                    }
                }

                return response()->json(['message' => 'User Logged', 'params' => ['auth_secure_token' => $session->auth_secure_token]], 200);
            } else {

                return response()->json(['errors' => ['login_or_password' => [trans('auth.invalid-credentials')]]], 401);
            }
        }
    }
}
