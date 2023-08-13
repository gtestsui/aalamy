<?php


namespace App\Http\Traits;


trait AuthorizesAfterValidation
{
    public function authorize()
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $validator->failed() && ! $this->authorizeAfterValidate()) {
//                dd($this->authorizeAfterValidate());
                $this->failedAuthorization();
            }
        });
    }

//    abstract public function authorizeAfterValidate();
    public function authorizeAfterValidate(){
        return true;
    }
}
