<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;

class SurveyAnswerClass
{

    public static function deleteAllSurveyAnswersBySurveyId($surveyId){
        DiscussionCornerSurveyUser::where('survey_id',$surveyId)->delete();
    }

    public function addSurveyAnswers(DiscussionCornerSurveyUser $surveyUser,array $answers){
        $requiredQuestionIdsByServeyId = DiscussionCornerSurveyQuestion::where('survey_id',$surveyUser->survey_id)
            ->where('is_required',true)->pluck('id')->toArray();
        $prepareAnswersForCreate = [];
        $answeredQuestionIds = [];
        $this->prepareAnswerArrayForCreate($answers,$surveyUser,$prepareAnswersForCreate,$answeredQuestionIds);
        $this->checkAllRequiredQuestionAreAnswered($requiredQuestionIdsByServeyId, $answeredQuestionIds);
        DiscussionCornerSurveyUserAnswer::insert($prepareAnswersForCreate);
    }

    public function prepareAnswerArrayForCreate(array $answers,$surveyUser,&$prepareAnswersForCreate,&$answeredQuestionIds){

        list($answeredQuestionIds,$allSelectedChoiceIds) =
            $this->checkQuestionsBelongsToSurveyAndEachSelectedChoiceBelongsToHisQuestion(
            $surveyUser->survey_id,$answers
        );

        $questions = DiscussionCornerSurveyQuestion::whereIn('id',$answeredQuestionIds)
            ->get();

        foreach ($answers as $answerObj){
            //get defined question from question collection
            $question = $questions->where('id',$answerObj['question_id'])->first();

            $this->checkIsRequiredQuestion($question,$answerObj);

//            $answeredQuestionIds[] = $answerObj['question_id'];
            $prepareAnswersForCreate [] = [
                'survey_user_id' => $surveyUser->id,
                'question_id' => $answerObj['question_id'],
                'choice_id' => isset($answerObj['choice_id'])
                    ?$answerObj['choice_id']
                    :null,
                'written_answer' => isset($answerObj['choice_id'])
                    ?null
                    :$answerObj['written_answer'],
            ];
        }
        //increment the counter of all selected choices to this survey
        DiscussionCornerSurveyQuestionChoice::whereIn('id',$allSelectedChoiceIds)
            ->increment('counter');
    }

    /**
     * we used this hard code to decrease the query count to database
     *
     */
    public function checkQuestionsBelongsToSurveyAndEachSelectedChoiceBelongsToHisQuestion($surveyId,$answers): array
    {
        //contains all question id sent in request from client
        $allAnsweredQuestionIds = []/*array_column($answers, 'question_id')*/;
        $allSelectedChoiceIds = []/*array_column($answers, 'choice_id')*/;
        $countOfAnsweredMultiChoiceQuestion = 0;
        $questionChoicesByQuestionAndChoiceIdsQuery = DiscussionCornerSurveyQuestionChoice::query();
        foreach ($answers as $key=>$answerObj){
            $allAnsweredQuestionIds[] = $answerObj['question_id'];
            if(isset($answerObj['choice_id'])){
                $allSelectedChoiceIds[] = $answerObj['choice_id'];
                $countOfAnsweredMultiChoiceQuestion++;
                $questionChoicesByQuestionAndChoiceIdsQuery->orWhere(function ($q)use($answerObj){
                    return $q->where([
                        ['id',$answerObj['choice_id']],
                        ['question_id',$answerObj['question_id']],
                    ]);
                });
            }

        }

        $this->checkEachChoiceBelongsToHisQuestion(
            $questionChoicesByQuestionAndChoiceIdsQuery,$countOfAnsweredMultiChoiceQuestion
        );

        $this->checkAllQuestionsBelongsToSurvey($surveyId,$allAnsweredQuestionIds);

        return [$allAnsweredQuestionIds,$allSelectedChoiceIds];

    }

    public function checkAllQuestionsBelongsToSurvey($surveyId,$allSelectedChoiceIds){
        //if there is at least one question sent in request doesn't belong to survey
        $questionDoesntBelongsToSurvey = DiscussionCornerSurveyQuestion::whereIn('id',$allSelectedChoiceIds)
            ->where('survey_id','!=',$surveyId)
            ->first();
        if(!is_null($questionDoesntBelongsToSurvey))
            throw new ErrorMsgException('invalid question with surveys');
    }

    public function checkEachChoiceBelongsToHisQuestion($questionChoicesByQuestionAndChoiceIdsQuery,$countOfAnsweredMultiChoiceQuestion){
        if($countOfAnsweredMultiChoiceQuestion==0)
            return true;
        $questionChoicesByQuestionAndChoiceIds =
            $questionChoicesByQuestionAndChoiceIdsQuery->get();

        //if the count doesnt equals that mean there is
        //  submitted choice id doesn't belong to his question
        if(count($questionChoicesByQuestionAndChoiceIds) != $countOfAnsweredMultiChoiceQuestion)
            throw new ErrorMsgException('there is choice doesnt belongs to question');


    }


//    public function checkQuestionIdBelongsToSurvey($questionId,$surveyId){
//        $question = DiscussionCornerSurveyQuestion::where('id',$questionId)
//            ->where('survey_id',$surveyId)
//            ->firstOrFail();
//        return $question;
//    }

//    public function checkChoiceIdBelongsToQuestion($choiceId,$questionId){
//        $choice = DiscussionCornerSurveyQuestionChoice::where('id',$choiceId)
//            ->where('question_id',$questionId)
//            ->firstOrFail();
//        return $choice;
//    }

    public function checkIsRequiredQuestion(DiscussionCornerSurveyQuestion $question,$answerObj){
        if($question->is_required){
            //check question type is multi_choice
            if($question->question_type == config('DiscussionCorner.panel.survey_question_types.choice')
                && !isset($answerObj['choice_id']))
                throw new ErrorMsgException(transMsg('question_required',ApplicationModules::DISCUSSION_CORNER_MODULE_NAME));
            //check question type is fill_text
            if($question->question_type == config('DiscussionCorner.panel.survey_question_types.fill_text')
                && !isset($answerObj['written_answer']))
                throw new ErrorMsgException(transMsg('question_required',ApplicationModules::DISCUSSION_CORNER_MODULE_NAME));
        }
    }

    public function checkAllRequiredQuestionAreAnswered($requiredQuestionIds,$answeredQuestionIds){
        /**
         * check if all required questions are answered and there is no one missed
        this array_diff here its return all values in $requiredQuestionIds array
        and not in $answeredQuestionIds array
         * and if that happen that mean we have a required question at least missed
         */
        if(count(array_diff($requiredQuestionIds, $answeredQuestionIds))>0)
            throw new ErrorMsgException(transMsg('question_required',ApplicationModules::DISCUSSION_CORNER_MODULE_NAME));

    }

}
