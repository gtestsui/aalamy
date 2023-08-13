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
     * @uses search('lara',['name','job'])
     * @uses search('lara',['name','job','Work.name']) //Work is relation name
     * @uses search('lara',['name','job','Work.name','Location.[country,state,city]]) // if you want to search in relation in many fields
     * @uses search('lara',[]) or search('lara') will search in model searchable field $mySearchableFields
     * @uses search('lara',[],['Work'])  will search in model searchable fields $mySearchableFields and in searchable fields in the relation Work
     * @uses search('lara',[],['Work.Location'])  will search in model searchable fields $mySearchableFields and in searchable fields in the relation Work and searchable fields in Location
     * but what if i want to search in to relation related with Work like Location and User so
     * @uses search('lara',[],['Work'=>['Location','User']])  will search in model searchable fields $mySearchableFields and in searchable fields in the relation Work and searchable fields in Location and in User searchable fields
     * @param string $key (querySearchKey)
     * @param array $fields (array of strings) if its empty then we will search
     * in getSearchableFields array
     * @param array $relations (array of relation names you want to search on all them fields too by relation)
     * @param array $expressionFields its explained bellow in method isExpressionField()
     * @return Builder
     * @throws ErrorMsgException
     * if you send just the key will call getSearchableFields() method
     * that return [] (if you didn't override it before)
     * you can override it to put your model searchable fields
     * to make all default search on it
     */
    public function scopeSearch($query,$searchKey,array $fields=[],array $relations=[],array $expressionFields=[]){

        if(is_null($searchKey) || empty($searchKey))
            return $query;

        if(empty($fields))
            $fields = $query->getModel()->getSearchableFields();

        $query->where(function($query)use($fields,$relations,$searchKey,$expressionFields){

            if(empty($fields))//if we have used relation and its mySearchableFields its empty that mean the query will execute ( or exists .....) without any conditions ,and it's always true
                $query->where('id',-1);

            //search in model searchable fields
            foreach ($fields as $index => $field) {
                if ($this->isRelation($field)) {
                    list($relation,$relationFields) = $this->analysesRelationFields($field);

                    $query->orWhereHas($relation, function ($q) use ($relationFields, $searchKey, $expressionFields) {
                        $q->search($searchKey, $relationFields, $expressionFields);
                    });

                } else {
                    $query->orWhere($field, 'like', '%' . $searchKey . '%');

                }

            }

            //search in relation fields
            foreach ($relations as $relationKey=>$value) {
                /**
                 * @var array $nestedRelationsInManyWays
                 * @var string $relationName
                 */
                list($relationName, $nestedRelationsInManyWays) =
                    $this->analysesNestedInManyWays($relationKey,$value);//User=>[Target,Teacher..]

                //the last item of nested relation will be empty
                if (empty($relationName))
                    return $query;

                list($relationName, $nestedRelations) =
                    $this->analysesNestedRelation($relationName);//User.Target...

                /**
                 * @var array $finalNestedRelations
                 */
                $finalNestedRelations = [$nestedRelations];
                if(!empty($nestedRelationsInManyWays)){
                    //didn't reach to the last nestedRelation yet
                    if(!empty($nestedRelations))
                        $finalNestedRelations = [$nestedRelations=>$nestedRelationsInManyWays];
                    else
                        $finalNestedRelations = $nestedRelationsInManyWays;
                }

//            echo $query->getModel()->getTable(). ' \n';
                if (!method_exists($query->getModel(), $relationName))
                    throw new ErrorMsgException('invalid relation name');
                $query->orWhereHas($relationName, function ($q) use ($searchKey, $finalNestedRelations) {
                    return $q->search($searchKey, [], $finalNestedRelations,['s']);
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
//        return $this->mySearchableFields ?? $this->fillable;
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
    public function isRelation($field):bool
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


    private function analysesNestedRelation($relationName){
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


    /**
     * when trying to search on 2 or more relation for defined relation
     * ex:
     *  ['User'=>['Student','Address']]
     */
    private function analysesNestedInManyWays($key,$value){
        $relationName = $value;
        $moreThanOneNestedRelations = [];
        if(!is_int($key)){
            $relationName = $key;
            $moreThanOneNestedRelations = $value;
        }
        return [$relationName,$moreThanOneNestedRelations];
    }






}
