<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes;

use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion;

class SurveyQuestionClass
{


//    public $surveyId,$question;
//    public function __construct($request,$surveyId)
//    {
//        $this->surveyId = $surveyId;
//        $this->question = $request->question;
//    }

    public function createOrUpdateOrDeleteMultiple(array $questions/*=null*/,$surveyId/*=null*/){
//        if(isset($questions) && isset($surveyId)){//check if you want multiple insert and update an delete
            $questionCrudArrays = $this->prepareCrudArrays($questions,$surveyId);
            $this->deleteMultiQuestions($questionCrudArrays['idsToKeepAwayFromDeleteArray'],$surveyId);
            $this->createMultiQuestions($questionCrudArrays['questionsForCreateArray']/*,$survey*/);
            $this->updateMultiQuestions($questionCrudArrays['questionsForUpdateArray']);
//        }else{//check if you want insert just one
//            $question = DiscussionCornerSurveyQuestion::create($this->all());
//            return $question;
//        }
    }


    public function prepareCrudArrays(array $questions,int $surveyId){
        $questionsForCreateArray        = [];
        $questionsForUpdateArray        = [];
        $idsToKeepAwayFromDeleteArray = [];
        foreach ($questions as $key=>$questionObj){
            if($this->forCreate($questionObj)){//this new answers
                $questionsForCreateArray[] = $this->prepareForCreateArray($questionObj,$surveyId);
            }else{//this answers for update
                $questionsForUpdateArray[$questionObj['id']] = $this->prepareForUpdateArray($questionObj);
                $idsToKeepAwayFromDeleteArray[] = $this->prepareForIdsToKeepAwayFromDeleteIdsArray($questionObj);
            }
        }
        return [
            'questionsForCreateArray'       => $questionsForCreateArray,
            'questionsForUpdateArray'       => $questionsForUpdateArray,
            'idsToKeepAwayFromDeleteArray'  => $idsToKeepAwayFromDeleteArray,
        ];
    }

    public function forCreate($questionObjFromRequest){
        if(!isset($questionObjFromRequest['id']))
            return true;
        return false;
    }

    public function prepareForCreateArray($questionObjFromRequest,$surveyId){
        $questionObjFromRequest['survey_id'] = $surveyId;
        $questionObjFromRequest['created_at'] = now();
        return $questionObjFromRequest;
    }

    public function prepareForUpdateArray($questionObjFromRequest){
        return $questionObjFromRequest;
    }

    public function prepareForIdsToKeepAwayFromDeleteIdsArray($questionObjFromRequest){
        return $questionObjFromRequest['id'];
    }

    /**
     * @param bool $deleteOthers
     * if true then delete the items doesn't belong to $questionIds param
     * if false then delete the items belongs to $questionIds
     * and we get the items then soft delete for each one because the observer doesnt work without this step
     * or we can delete them by one query and then get all child relations and then delete them manually too
     */
    public function deleteMultiQuestions(array $questionIds,$surveyId,$deleteOthers=true){
        if($deleteOthers){
            $questions = DiscussionCornerSurveyQuestion::whereNotIn('id',$questionIds)
                ->where('survey_id',$surveyId)
                                ->delete();

//                ->get();

//                ->delete();
//            // we looped to make listener softDelete work
//            foreach ($questions as $question){
//                $question->softDeleteObject();
//            }

        }
    }

    /**
     * @param array $questions
     * array of arrays prepared for fast create
     */
    public function createMultiQuestions(array $questions/*,$survey*/){
        if(count($questions)>0){
            foreach($questions as $questionObj){
                if(!isset($questionObj['choices'])){
                    $question = DiscussionCornerSurveyQuestion::create($questionObj);
                    continue;
                }

                $choices = $questionObj['choices'];
                unset($questionObj['choices']);
                $question = DiscussionCornerSurveyQuestion::create($questionObj);
                $surveyQuestionChoiceClass = new SurveyQuestionChoiceClass();
                $surveyQuestionChoiceClass->createOrUpdateOrDeleteMultiple($choices,$question->id);

            }
        }

    }

    public function updateMultiQuestions(array $questions){
        if(count($questions)>0) {
            foreach ($questions as $questionId => $questionObj) {
                unset($questionObj['id']);
//                $question = DiscussionCornerSurveyQuestion::findOrFail($questionId)
//                    ->update($questionObj);

                if(!isset($questionObj['choices'])){
                    DiscussionCornerSurveyQuestion::where('id', $questionId)
                        ->update($questionObj);
                    continue;
                }
                $choices = $questionObj['choices'];
                unset($questionObj['choices']);

                DiscussionCornerSurveyQuestion::where('id', $questionId)
                    ->update($questionObj);

                $surveyQuestionChoiceClass = new SurveyQuestionChoiceClass();
                $surveyQuestionChoiceClass->createOrUpdateOrDeleteMultiple($choices,$questionId);
            }
        }
    }

}
