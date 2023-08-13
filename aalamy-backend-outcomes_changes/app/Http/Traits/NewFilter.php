<?php


namespace App\Http\Traits;


use App\Exceptions\ErrorMsgException;

trait NewFilter
{



    public function scopeFilter($query,?array $arg1=null,?array $arg2=null){
        $filters = request()->filter;
        if(!isset($filters))
            return $query;

        $args = func_get_args();
        if(count($args)<=1)
            return $query;

        $exactly = [];
        $in = [];
        $like = [];
        for($i=1;$i<=$args;$i++){
            if($this->isExactlyFilter($args[$i])){
                $exactly = $args[$i]['exactly'];
            }
            elseif($this->isInFilter($args[$i])){
                $in = $args[$i]['in'];
            }
            elseif($this->isLikeFilter($args[$i])){
                $like = $args[$i]['like'];
            }
        }

        foreach ($filters as $column=>$filter){
            if(isset($exactly[$column])){
                $query->where($column,$filters[$column]);
            }

            elseif(isset($in[$column])){
                $query->whereIn($column,$filters[$column]);
            }

            elseif(isset($like[$column])){
                $query->where($column,'Like',"%$filters[$column]%");
            }
            else{
                throw new ErrorMsgException('invalid filter name from request');
            }

        }


//        for($i=1;$i<=$args;$i++){
//            if($this->isExactlyFilter($args[$i])){
//                foreach ($args[$i]['exactly'] as $column=>$value){
//                    if(!isset($filters[$column]))
//                        throw new ErrorMsgException('invalid filter name from request');
//                    $query->where($column,$filters[$column]);
//                }
//
//            }
//
//
//            elseif ($this->isInFilter($args[$i])){
//                foreach ($args[$i]['in'] as $column=>$value){
//                    if(!isset($filters[$column]))
//                        throw new ErrorMsgException('invalid filter name from request');
//                    if(!is_array($filters[$column]))
//                        throw new ErrorMsgException('you are using in filter on not  an array element');
//                    $query->whereIn($column,$filters[$column]);
//                }
//
//            }
//
//            else{
//                foreach ($args[$i] as $column=>$value){
//                    if(!isset($filters[$column]))
//                        throw new ErrorMsgException('invalid filter name from request');
//                    $query->where($column,'Like',"%$filters[$column]%");
//                }
//            }
//        }

        return $query;
    }


    private function isExactlyFilter(array $arg){
        if(isset($arg['exactly']))
            return true;
        return  false;
    }

    private function isInFilter(array $arg){
        if(isset($arg['in']))
            return true;
        return  false;
    }

    private function isLikeFilter(array $arg){
        if(isset($arg['like']))
            return true;
        return  false;
    }

    public static function setExactlyColumns(array $arg){
        return [
            'exactly' => $arg
        ];
    }

    public static function setInColumns(array $arg){
        return [
            'in' => $arg
        ];
    }

    public static function setLikeColumns(array $arg){
        return [
            'like' => $arg
        ];
    }






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
