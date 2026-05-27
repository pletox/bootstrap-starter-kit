<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// STARTER-KIT-TENANCY:registration-imports
use Illuminate\Support\Facades\DB;
// END-STARTER-KIT-TENANCY:registration-imports

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'workspace_name' => ['required', 'string', 'min:2', 'max:120'],
        ])->validate();

// STARTER-KIT-TENANCY:registration-create
        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $tenant = $user->tenants()->create([
                'name' => $input['workspace_name'],
                'owner_id' => $user->getKey(),
            ], [
                'role' => 'owner',
            ]);

            $user->forceFill(['current_tenant_id' => $tenant->getKey()])->save();

            return $user;
        });
// END-STARTER-KIT-TENANCY:registration-create
    }
}
