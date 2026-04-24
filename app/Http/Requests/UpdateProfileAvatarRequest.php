<?php

namespace App\Http\Requests;

use App\Models\AvatarOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar_option_id' => [
                'nullable',
                Rule::exists(AvatarOption::class, 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar_option_id.exists' => 'Please choose one of the active avatars.',
        ];
    }
}
