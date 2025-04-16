<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    // public function update(User $user, array $input): void
    // {
    //     Validator::make($input, [
    //         'current_password' => ['required', 'string', 'current_password:web'],
    //         'password' => $this->passwordRules(),
    //     ], [
    //         'current_password.current_password' => __('The provided password does not match your current password.'),
    //     ])->validateWithBag('updatePassword');

    //     $user->forceFill([
    //         'password' => Hash::make($input['password']),
    //     ])->save();
    // }

        public function update($user, array $input)
    {
        Validator::make($input, [
            'current_password' => ['nullable'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->after(function ($validator) use ($user, $input) {
            // Only check current password if it exists
            if ($user->password && !Hash::check($input['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('The provided password does not match your current password.'),
                ]);
            }
        })->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
