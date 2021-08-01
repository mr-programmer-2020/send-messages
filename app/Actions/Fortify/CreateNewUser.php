<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Telegram;
use App\Notifications\SendMail;
use App\Jobs\SendEmailJob;
use App\Jobs\SendTelegramJob;
use Carbon\Carbon;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telegram_user_id' => ['required', 'integer', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'telegram_user_id' => $input['telegram_user_id'],
            'password' => Hash::make($input['password']),
        ]);

        //send telegram notification
        $job = (new SendTelegramJob())->delay(Carbon::now()->addSeconds(5));
        dispatch($job);

        //send mail notification
        $job = (new SendEmailJob())->delay(Carbon::now()->addSeconds(5));
        dispatch($job);

        return $user;
    }
}


