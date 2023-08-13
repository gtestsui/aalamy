<?php


namespace App\Http\Traits;


use App\Exceptions\ErrorMsgException;

trait Filter
{


    public function scopeFilterBy($query,array $fields){
        $modelFields = $this->getModelFields();
        foreach ($fields as $key=>$field){
            if(!in_array($key,$modelFields))
                throw new ErrorMsgException('filter by invalid field name '.$key);
            $query->when(isset($field),function ($q)use($key,$field){
                return $q->where($key,$field) ;
            });
        }
        return $query;
    }

    /**
     * return if has $fillable
     * else return []
     */
    public function getModelFields():array
    {
        if(isset($this->fillable))
            return $this->fillable;
        return [];
    }


}
