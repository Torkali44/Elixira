<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDxnTeamRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'member_code' => ['nullable', 'string', 'max:100'],
            'team_name' => ['required', 'string', 'max:255'],
            'team_size' => ['required', 'integer', 'min:1', 'max:500'],
            'country' => ['required', 'in:KSA,UAE'],
            'team_members' => ['nullable', 'array'],
            'team_members.*.name' => ['nullable', 'string', 'max:255'],
            'team_members.*.contact' => ['nullable', 'string', 'max:255'],
            'team_goal' => ['nullable', 'string', 'max:1000'],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
