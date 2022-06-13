<?php


namespace App\Http\Traits;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

trait RequestValidationTrait
{
    //concat validation errors in unique single $key => $value
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            $errorsArray=[];
            foreach ($errors as $key=>$error){
                $errorsArray[$key]=implode(" , \n ",array_values($error));
            }
            throw new HttpResponseException(
                response()->json(['message'=>'Validation Error','errors'=>$errorsArray],422)
            );
        }

        parent::failedValidation($validator);
    }

}

