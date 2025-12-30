<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAnyRole(['superadmin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name'  => [ 'required', 'string', 'min:3', 'max:255' ],
            'email' => [ 'required', 'email', 'max:255', 
                            Rule::unique('invitations', 'email')->whereNull('accepted_at')
                        ],

            'role' => [ 'required', 
                        Rule::in(['admin', 'member'])
                    ],

        ];

        if($user->hasRole('superadmin')) {
            // $rules['company_name'] = [ 'required', 'string', 'min:3', 'max:255' ];
            $rules['role'] = ['required', Rule::in(['admin'])];
        }

        if($user->hasRole('admin')) {
            // $rules['user_name'] = [ 'required', 'string', 'min:3', 'max:255' ];
            $rules['role'] = ['required', Rule::in(['admin', 'member'])];
        }

        return $rules;
    }

    public function messages(): array
    {
        return[
            'email.unique' => 'An active invitation already exists for this email.',
            'name' => 'Name is required',
            // 'company_name' => 'Company name is required for creating new client',
            // 'user_name' => 'User name is required for sending invite to new user',
            'role.in' => 'Role not valid',
        ];
    }
}
