<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('superadministrator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'nullable',
                Rule::unique('companies', 'name')->withoutTrashed(),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('companies', 'email')->withoutTrashed(),
            ],
            'phone' => [
                'nullable',
                Rule::unique('companies', 'phone')->withoutTrashed(),
            ],
        ];
    }
}
