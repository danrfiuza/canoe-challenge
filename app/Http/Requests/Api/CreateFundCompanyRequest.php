<?php

namespace App\Http\Requests\Api;

use App\Rules\UniqueFundCompany;

class CreateFundCompanyRequest extends ApiFormRequest
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
            'fund_id' => [
                'required',
                'integer',
                'exists:funds,id',
                new UniqueFundCompany($this->company_id),
            ],
            'company_id' => 'required|integer|exists:companies,id',
        ];
    }
}
