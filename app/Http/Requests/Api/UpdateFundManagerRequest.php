<?php

namespace App\Http\Requests\Api;

class UpdateFundManagerRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'start_year' => 'required|integer',
            'fund_manager_id' => 'required|integer|exists:fund_managers,id'
        ];
    }
}
