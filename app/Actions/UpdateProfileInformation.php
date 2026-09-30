<?php

namespace App\Actions;

use App\Concerns\ProfileValidationRules;
use App\Models\User;

class UpdateProfileInformation
{
    use ProfileValidationRules;

    /**
     * Validate and update the user's profile information.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function update(User $user, array $input): array
    {
        $validated = validator($input, $this->profileRules($user->id), [], [
            'name' => 'name',
            'email' => 'email',
        ])->validate();

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $validated;
    }
}
