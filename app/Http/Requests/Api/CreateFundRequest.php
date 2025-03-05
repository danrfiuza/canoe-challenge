<?php

namespace App\Http\Requests\Api;

class CreateFundRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|unique:fund_managers,name|max:255',
            'start_year' => 'integer',
            'fund_manager_id' => 'integer|exists:fund_managers,id',
            'fund_aliases' => 'array|nullable',
            // 'fund_aliases.*' => 'string|unique:fund_aliases,alias|max:255',
            'fund_aliases.*' => 'string|max:255',
        ];
    }
}
