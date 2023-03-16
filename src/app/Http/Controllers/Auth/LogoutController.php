<?php


namespace App\Http\Controllers\Auth;


use App\Exceptions\AuthLogoutException;
use App\Http\Controllers\Controller;
use App\Models\Sessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $token = $request->hasHeader('Auth-Secure-Token') ? $request->headers->get('Auth-Secure-Token') : null;

        if (!$token) {
            return response()->json(['errors' => trans('auth.miss-configuration')], 422);
        }

        $session = Sessions::where('auth_secure_token', $token)->first();

        if (!$session){
            return response()->json(['errors' => trans('auth.logout')], 422);
        }

        $active_sessions = Sessions::where('user_id', $session->user_id)
            ->where('auth_secure_token', '<>', $token)
            ->where('status', 'active')
            ->get();

        if (!empty($active_sessions)) {
            foreach ($active_sessions as $active_session) {
                Sessions::where('id', $active_session->id)
                    ->update(['change_by' => 'logout', 'disabled_by_session_id' => $session->id, 'status' => 'inactive']);
            }
        }

        if ($session->status === 'active') {
            Sessions::where('id', $session->id)
                ->update(['change_by' => 'logout', 'status' => 'inactive']);
        }

        return response()->json(['errors' => trans('auth.logout')], 200);
    }
}
