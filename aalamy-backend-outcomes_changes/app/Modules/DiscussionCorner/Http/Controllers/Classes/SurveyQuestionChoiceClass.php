<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes;

use Carbon\Carbon;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;

class SurveyQuestionChoiceClass
{


//    public $surveyId,$question;
//    public function __construct($request,$surveyId)
//    {
//        $this->surveyId = $surveyId;
//        $this->question = $request->question;
//    }



    public function createOrUpdateOrDeleteMultiple(array $choices/*=null*/,$questionId/*=null*/){
//        if(isset($choices) && isset($question)){//check if you want multiple insert and update an delete
            $choiceCrudArrays = $this->prepareCrudArrays($choices,$questionId);
            $this->deleteMultiChoices($choiceCrudArrays['idsToKeepAwayFromDeleteArray'],$questionId);
            $this->createMultiChoices($choiceCrudArrays['choicesForCreateArray']/*,$questionId*/);
            $this->updateMultiChoices($choiceCrudArrays['choicesForUpdateArray']);
//        }else{//check if you want insert just one
//            $question = DiscussionCornerSurveyQuestion::create($this->all());
//            return $question;
//        }
    }


    public function prepareCrudArrays(array $choices,int $questionId){
        $choicesForCreateArray        = [];
        $choicesForUpdateArray        = [];
        $idsToKeepAwayFromDeleteArray = [];
        foreach ($choices as $key=>$choiceObj){
            if($this->forCreate($choiceObj)){//this new answers
                $choicesForCreateArray[] = $this->prepareForCreateArray($choiceObj,$questionId);
            }else{//this answers for update
                $choicesForUpdateArray[$choiceObj['id']] = $this->prepareForUpdateArray($choiceObj);
                $idsToKeepAwayFromDeleteArray[] = $this->prepareForIdsToKeepAwayFromDeleteIdsArray($choiceObj);
            }
        }
        return [
            'choicesForCreateArray'       => $choicesForCreateArray,
            'choicesForUpdateArray'       => $choicesForUpdateArray,
            'idsToKeepAwayFromDeleteArray'  => $idsToKeepAwayFromDeleteArray,
        ];
    }

    public function forCreate($choiceObjFromRequest){
        if(!isset($choiceObjFromRequest['id']))
            return true;
        return false;
    }

    /**
     * @param mixed|array|string $choiceObjFromRequest
     * $choiceObjFromRequest will come from request as object always even in  the store request
     * in store will be like : { "choice":QuestionTypes}
     * in update will be like : { "id":1,"choice":QuestionTypes}
     *
     */
    public function prepareForCreateArray($choiceObjFromRequest,$questionId){
//        if(!is_array($choiceObjFromRequest)){
//            $choice = $choiceObjFromRequest['choice'];
//            $choiceObjFromRequest = [];
//            $choiceObjFromRequest['choice'] =  $choice;
//        }
        $choiceObjFromRequest['question_id'] = $questionId;
        $choiceObjFromRequest['created_at'] = now();
        return $choiceObjFromRequest;
    }

    public function prepareForUpdateArray($choiceObjFromRequest){
        return $choiceObjFromRequest;
    }

    public function prepareForIdsToKeepAwayFromDeleteIdsArray($choiceObjFromRequest){
        return $choiceObjFromRequest['id'];
    }

    /**
     * @param bool $deleteOthers
     * if true then delete the items doesn't belong to $choiceIds param
     * if false then delete the items belongs to $choiceIds
     */
    public function deleteMultiChoices(array $choiceIds,$questionId,$deleteOthers=true){
        if($deleteOthers)
            DiscussionCornerSurveyQuestionChoice::whereNotIn('id',$choiceIds)
                ->where('question_id',$questionId)
                ->delete();
    }

    /**
     * @param array $questions
     * array of arrays prepared for fast create
     */
    public function createMultiChoices(array $choices/*,$question*/){
        if(count($choices)>0){
            DiscussionCornerSurveyQuestionChoice::insert($choices);
        }
    }

    public function updateMultiChoices(array $choices){
        if(count($choices)>0) {
            foreach ($choices as $choiceId => $choiceObj) {
                unset($choiceObj['id']);
                DiscussionCornerSurveyQuestionChoice::where('id', $choiceId)
                    ->update($choiceObj);
            }
        }
    }

}
