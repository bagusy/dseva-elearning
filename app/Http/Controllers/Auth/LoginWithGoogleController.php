<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FormPreparation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginWithGoogleController extends Controller
{
    use FormPreparation;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback()
    {
        try {
            $userData = Socialite::driver('google')->user();
            $user = User::where('social_id', $userData['id'])->first();

            if ($user) {
                auth()->login($user);
                return redirect('/home');
            }

            $existingUser = User::whereEmail($this->filterGmailValidation($userData['email']))->first();
            if ($existingUser) {
                if (is_null($existingUser['email_verified_at'])) {
                    $existingUser->markEmailAsVerified();
                }
                $existingUser['social_id'] = $userData['id'];
                $existingUser['social_type'] = 'google';
                $existingUser->save();
                auth()->login($existingUser);
                return redirect('/home');
            }

            $newUser = User::create([
                'name' => $userData['name'],
                'email' => $this->filterGmailValidation($userData['email']),
                'social_id' => $userData['id'],
                'social_type' => 'google',
                'password' => bcrypt(uniqid()),
                'avatar' => $userData['picture']
            ]);
            $newUser->markEmailAsVerified();
            $newUser->assignRole(User::ROLE_USER_ADMIN);

            auth()->login($newUser);
            return redirect('/home');

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->withErrors('Something went wrong');
        }
    }
}
