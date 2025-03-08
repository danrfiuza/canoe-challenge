<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\FundCompanyService;

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
        if ((new FundCompanyService())->isFundCompanyUnique($value, $this->companyId)) {
            $fail('The combination of fund_id and company_id already exists.');
        }
    }
}
