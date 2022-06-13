<?php

namespace App\Rules;

use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;

class UniqueNationalID implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
         $count= Employee::where([
            ['national_id',$value],
            ['active',true],
        ])->count();
         return $count<1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The national id has been taken with active employee';
    }
}
