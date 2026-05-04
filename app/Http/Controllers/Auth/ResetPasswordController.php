<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\FormPreparation;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, FormPreparation;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $request['password'] = $this->validateStrongPassword($request['password'], 'password');

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });



        if ($response == Password::PASSWORD_RESET) {
            $user = User::whereEmail($request['email'])->first();
            if ($user && !$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                $user->save();
            }
            auth()->logout();
            return redirect('/login')->withMessage('Password updated. Please login to continue');
        }

        return $this->sendResetFailedResponse($request, $response);
    }
}
