<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\FundCompany;

class UniqueFundCompany implements ValidationRule
{
    public function __construct(private int $companyId) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (FundCompany::where('fund_id', $value)
            ->where('company_id', $this->companyId)
            ->exists()
        ) {
            $fail('The combination of fund_id and company_id already exists.');
        }
    }
}