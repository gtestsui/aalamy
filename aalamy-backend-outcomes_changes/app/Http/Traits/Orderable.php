<?php


namespace App\Http\Traits;


use App\Exceptions\ErrorMsgException;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;

trait Orderable
{

    protected static function bootOrderable()
    {
        static::addGlobalScope(new DefaultOrderByScope);
    }

    private $orderTypes = ['desc','asc'];
    /**
     * @param string $orderByFieldName the field you want to order by
     * @param string $orderType the name of type you want to order by
     * @param string[] $joinRelation contain the models path im joining with
     * to check if i want to order on field from it
     * @param array-key[] $joinRelation its accept
     * key and value -> the key is the path of the model and the value
     * its array of new names selected by (as) statement in sql
     * ex: select student.* , users.name as us_name  from.... join...
     *     so the function should be:
     *     Student::select(...)->join(...)
     *     ->order($field,$type,[
     *              User::class=>[us_name]
     *          ])
     *
     * @return Builder
     * @throws ErrorMsgException if the orderType is invalid
     * or if the field name is invalid
     */
    public function scopeOrder($query,$orderByFieldName,$orderType=null,array $joinRelation=[]){
        if(is_null($orderByFieldName) || empty($orderByFieldName))
            return $query;

        $this->checkValidOrderType($orderType);

        $this->checkValidFieldName($orderByFieldName,$joinRelation);

        $query->orderBy($orderByFieldName,$orderType);
        return $query;
    }

    /**
     * check if the model who use this trait has $orderableFields
     * else has $fillable
     * else return []
     */
    public function getOrderableFields():array
    {
        $defaultFileds = $this->prepareDefaultTimestampFileds();
        if(isset($this->orderableFields))
            return array_merge($defaultFileds,$this->orderableFields);
        if(isset($this->fillable))
            return array_merge($defaultFileds,$this->fillable);
        return $defaultFileds;
    }

    public function prepareDefaultTimestampFileds():array
    {
        $defaultFileds = [];
        if(!isset($this->order_by_default_created_at) || $this->order_by_default_created_at)
            $defaultFileds [] = 'created_at';
        if(!isset($this->order_by_default_updated_at) || $this->order_by_default_updated_at)
            $defaultFileds [] = 'updated_at';
        return $defaultFileds;
    }

    private function checkValidOrderType(&$orderType){
        if(!in_array($orderType,$this->orderTypes,0) && !is_null($orderType))
            throw new ErrorMsgException('invalid order type');
        if(is_null($orderType))
            $orderType = $this->orderTypes[0];
        return $orderType;
    }

    private function checkValidFieldName($orderByFieldName,array $joinRelation){
        if(in_array($orderByFieldName,$this->getOrderableFields(),0))
           return true;
        if(count($joinRelation)>0){
            if($this->checkValidFieldNameInRelations($orderByFieldName,$joinRelation))
                return true;
        }
        throw new ErrorMsgException('invalid order field name');

    }

    /** @param string[] $joinRelation contain the models path im joining with
    * to check if i want to order on field from it
    * @param array-key[] $joinRelation its accept
    * key and value -> the key is the path of the model and the value
    * its array of new names selected by (as) statement in sql
    * ex: select student.* , users.name as us_name  from.... join...
    *     so the function should be:
     *     Student::select(...)->join(...)
     *     ->order($field,$type,[
     *              User::class=>[us_name]
     *          ])
     */
    private function checkValidFieldNameInRelations($orderByFieldName,array $joinRelation){
        foreach ($joinRelation as $key=>$value){
            list($classModelPath,$newNamesSelected) =$this->analyse($key,$value);
            $this->checkValidModelPath($classModelPath);
            $model = new $classModelPath();
            if(in_array($orderByFieldName,$model->getOrderableFields(),0)
                || in_array($orderByFieldName,$newNamesSelected,0))
                return true;
        }
        return false;
    }

    private function analyse($key,$value){
        $newNamesSelected = [];
        $classModelPath = $value;
        if(is_array($value)){
            $newNamesSelected = $value;
            $classModelPath = $key;
        }

        return [$classModelPath,$newNamesSelected];
    }

    private function checkValidModelPath($classModelPath){
        if(!class_exists($classModelPath))
            throw new ErrorMsgException('invalid joined class name in order');

    }


















}
