<?php


namespace App\Http\Traits;


use App\Http\Controllers\Classes\ApiResponseClass;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use phpDocumentor\Reflection\Types\Boolean;

trait ModelSharedScopes
{

    /**
     * @param mixed $id int|null
     * retrieve the data depends on id else ignore the id
     */
    public function scopeByNullableId($query,$id=null){
        if(is_null($id))
            return $query;
        else
            return $query->where('id',$id);
    }




}
