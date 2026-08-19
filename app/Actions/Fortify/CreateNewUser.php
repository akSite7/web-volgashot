<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'login' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,login'],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $isFirstUser = ! User::query()->lockForUpdate()->exists();
            $user = User::create([
                'name' => $input['login'],
                'login' => $input['login'],
                'email' => Str::uuid().'@admin.local',
                'password' => $input['password'],
            ]);

            if ($isFirstUser) {
                $user->forceFill(['approved_at' => now()])->save();
            }

            return $user;
        });
    }
}
