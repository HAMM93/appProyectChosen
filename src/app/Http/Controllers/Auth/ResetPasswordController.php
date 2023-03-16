<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Email\EmailNotSendException;
use App\Exceptions\User\PasswordNotRedefinedException;
use App\Helpers\Response\ResponseAPI;
use App\Http\Controllers\Controller;
use App\Mail\RedefinedPasswordMail;
use App\Mail\ForgotPasswordMail;
use App\Models\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ResetPasswordController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws EmailNotSendException
     * @throws ValidationException
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email:rfc,filter'
        ]);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        } else {
            $user = User::where('email', $request->get('email'))->first();

            if (!$user)
                return ResponseAPI::unprocessableEntity(['message' => trans('auth.forgot-password-requested')]);

            $token = $this->prepareToken($user);

            if ($token) {
                try {
                    Mail::to($user->email)->send(new ForgotPasswordMail($user->name, $user->email, $token->token_reset_password));
                } catch (\Exception $e) {
                    throw new EmailNotSendException($e);
                }
            }

            return ResponseAPI::results(['message' => trans('auth.forgot-password-requested')]);
        }
    }

    /**
     * @param User $user
     * @return Model|\Illuminate\Http\JsonResponse
     */
    private function prepareToken(User $user)
    {
        $token = $user->resetPasswords()->create([
            'token_reset_password' => Str::uuid(),
            'expired_at' => Carbon::now()->addMinutes(10),
        ]);

        if (!$token)
            return ResponseAPI::unprocessableEntity(['errors' => trans('auth.generic-error')]);

        return $token;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws PasswordNotRedefinedException
     * @throws ValidationException
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email:rfc,filter',
            'token' => 'required|uuid',
            'new_password' => 'required|confirmed',
        ]);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $user = User::where('email', $request->get('email'))->select('id', 'name', 'email')->first();

        if (!$user)
            return ResponseAPI::unprocessableEntity(['message' => trans('auth.email-token-invalid')]);

        $resetPassword = ResetPassword::where('user_id', $user->id)
            ->where('token_reset_password', $request->get('token'))
            ->where('status', 'pending')
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$resetPassword)
            return ResponseAPI::unprocessableEntity(['message' => trans('auth.token-invalid-expired')]);

        try {
            $user->fill(['password' => $request->get('new_password')])->update();

            $resetPassword->fill(['status' => 'finished'])->update();
        } catch (Throwable $e) {
            throw new PasswordNotRedefinedException($e);
        }

        return ResponseAPI::results(['message' => trans('auth.password-updated'), 'params' => ['user_id' => $user->id]]);
    }
}
