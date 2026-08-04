<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\InvokableRule;

class AdultRule implements InvokableRule
{

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        // Calculate the age based on the provided date of birth
        $age = Carbon::parse($value)->age;
        if($age<18){
            $fail('Must be 18 years or older');
        }
    }
}
