<?php


namespace App\Http\Traits;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\User\Models\User;
use phpDocumentor\Reflection\Types\Boolean;

trait Searchable
{

    private $separatorBetweenRelationFields = ',';
    /**
     * notice: the search scope should be the first function called before other where function
     * @uses search('lara',['name','job','Work.name','Location.[country,state,city])
     * @param string $key (querySearchKey)
     * @param array $fields (array of strings) if its empty then we will search
     * in getSearchableFields array
     * @param array $relations (array of model paths you want to search on all them fields too by relation)
     * @param array $expressionFields its explained bellow in method isExpressionField()
     * @return Builder
     * @throws ErrorMsgException
     * if you send just the key will call getSearchableFields() method
     * that return [] (if you didnt override it before)
     * you can override it to put your model searchable fields
     * to make all default search on it
     */
    public function scopeSearch($query,$key,array $fields=[],array $relations=[],array $expressionFields=[]){
        if(is_null($key) || empty($key))
            return $query;

        if(empty($fields))
            $fields = $query->getModel()->getSearchableFields();


        $query->where(function($query)use($fields,$relations,$key,$expressionFields){
            foreach ($fields as $index => $field) {
                if ($this->isRelation($field)) {
                    list($relation,$relationFields) = $this->analysesRelationFields($field);

                    $query->orWhereHas($relation, function ($q) use ($relationFields, $key, $expressionFields) {
                        $q->search($key, $relationFields, $expressionFields);
                    });

                } else {
                    $query->orWhere($field, 'like', '%' . $key . '%');

                }

            }

            //search in relation fields
            foreach ($relations as $relationName) {
                if (empty($relationName))
                    return $query;
                list($relationName, $otherLevelOfMultipleRelation) = $this->analysesDirectRelation($relationName);

//            echo $query->getModel()->getTable(). ' \n';
                if (!method_exists($query->getModel(), $relationName))
                    throw new ErrorMsgException('invalid relation name');
                $query->orWhereHas($relationName, function ($q) use ($key, $otherLevelOfMultipleRelation) {
                    $q->search($key, [], [$otherLevelOfMultipleRelation]);
                });
            }
        });
        return $query;
    }

    /**
     * you should override this method and make it return array contain
     * your model fields to search in it
     */
    public function getSearchableFields(){
        return $this->mySearchableFields ?? [];
    }

    /**
     * check if the field exists as key in $expressionFields array
     * if exists then return the new value of it
     *else return his original value
     *we use this function for fields need query like
     * \DB::raw('CONCAT(fname," ",lname)')
     * example:
     * Model::search('lara',
     *              ['name','User.[concatName,country]'],
     *              ['concatName' => \DB::raw('CONCAT(fname," ",lname)')
     *            ])
     * so the original value of concatName its concatName
     * and the real value we should get it from the 3 parameter in search function
     * so that mean the real value of concatName its $array['concatName']
     * and its equal \DB::raw('CONCAT(fname," ",lname)')
     * so if the field is key in that array => the filed has real and original value
     */
    public function isExpressionField($field,array $expressionFields){
        //maybe the field will be Expression object
        if(!is_integer($field) && !is_string($field))
            return $field;
        if(count($expressionFields)>0 && array_key_exists($field,$expressionFields))
            return $expressionFields[$field];
        return $field;
    }


    /**
     * @param string $field
     * @return bool
     * check if the field string contains dot that mean this field is relationship
     */
    private function isRelation($field):bool
    {
        return str_contains($field,'.');
    }

    private function analysesRelationFields($field){
        list($relation,$relationFields) = explode('.',$field);

        $this->isValidRelationFormat($relation,$relationFields);
        $relationFields = $this->getRelationFields($relationFields);
        return [$relation,$relationFields];
    }

    private function isValidRelationFormat($relationName,$relationFields){
        if(empty($relationName))
            throw new ErrorMsgException('you have been missed the relation name - search action');

        if(empty($relationFields))
            throw new ErrorMsgException('you have been missed the parameter in relation '.$relation.' - search action');

    }


    private function analysesDirectRelation($relationName){
        $otherLevelOfMultipleRelation = '';
        if ($this->isMultipleRelation($relationName)) {
            list($relationName, $otherLevelOfMultipleRelation) =
                $this->separateFirstLevelOfMultipleRelation($relationName);
        }
        return [$relationName,$otherLevelOfMultipleRelation];
    }

    private function isMultipleRelation($field):bool
    {
        return str_contains($field,'.');
    }

    /**
     * separate the multiple relation to relation and other multiple
     * ex: the field is : Student.User.Address
     * then the result is ->
     * array[0] = 'Student'
     * array[1] = 'User.Address'
     */
    private function separateFirstLevelOfMultipleRelation($multipleRelationName){
        return explode('.',$multipleRelationName,2);
    }

    /**
     * @param string $relationFields
     * @return bool
     * @throws ErrorMsgException
     * check if the given string its array return true
     * else it single filed so we check if there is missing [ from the start
     * or ] in the last
     */
    private function isArrayRelationFields($relationFields):bool
    {
        $firstChar = $relationFields[0];
        $lastCharIndex = strlen($relationFields) - 1;
        $lastChar = $relationFields[$lastCharIndex];
        if ($firstChar == '[' && $lastChar == ']')
            return true;

        if(($firstChar == '[' && $lastChar != ']')||
            ($firstChar != '[' && $lastChar == ']'))
            throw new ErrorMsgException();

        return false;

    }

    /**
     * @param string $relationFields
     * @return array
     * explodes the string to fields
     */
    private function getFieldsFromArray($relationFields):array
    {
        $lastCharIndex = strlen($relationFields) - 1;
        // $lastCharIndex-1 because we dont want get until the char before lastChar
        $fields = substr($relationFields,1,$lastCharIndex-1);
        $fieldsArray = explode($this->separatorBetweenRelationFields,$fields);
        return $fieldsArray;
    }
    /**
     * @param string $relationFields
     * @return array
     * @throws ErrorMsgException
     */
    private function getRelationFields($relationFields):array
    {
        if($this->isArrayRelationFields($relationFields))
            $fieldsArray = $this->getFieldsFromArray($relationFields);
        else
            $fieldsArray = [$relationFields];
        return $fieldsArray;
    }





}
