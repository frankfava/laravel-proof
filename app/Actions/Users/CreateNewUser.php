<?php

namespace App\Actions\Users;

use App\Contracts\CreatesNewUser;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Single Action Class to create a new user
 * Allow programattic creation of new user
 *
 * Use for both admin management and registration
 */
class CreateNewUser implements CreatesNewUser
{
    use UserValidationRules;

    /** Create a new user model with trusted data */
    public function create(array $data)
    {
        // User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => isset($data['role']) ? $data['role'] : UserRole::default(),
            'active' => isset($data['active']) ? (bool) $data['active'] : true,
        ]);

        return $user;
    }

    /**
     * Create a validator for a New user
     * Also used in \App\Http\Requests\UserRequest
     */
    public function createValidator(array $data = [], array $customRules = [], array $customMessages = []): \Illuminate\Validation\Validator
    {
        $rules = [
            'name' => $this->nameRules(['required']),
            'email' => $this->uniqueEmailRules(['required']),
            'password' => $this->passwordRules(['required', 'confirmed']),
            'role' => $this->roleRules(['sometimes']),
            'active' => ['sometimes', 'boolean'],
            ...$customRules,
        ];

        // Messages
        $messages = [
            ...$this->validationMessages(),
            ...$customMessages,
        ];

        // Validate
        $validator = Validator::make($data, $rules, $messages);

        return $validator;
    }
}
