<?php


namespace Modules\QuestionBank\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion\QuestionManagementFactory;
use Modules\QuestionBank\Models\QuestionBank;

class QuestionServices
{

    /**
     * @return array
     * @note the order of item in returned array is important
     */
    public static function getCurrentQuestionType(string $questionType){
        $questionManageClass = QuestionManagementFactory::create($questionType);

        $isMultiChoice = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.multi_choice',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );
        $isTrueFalse = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.true_false',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );
        $isFillInBlank = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.fill_in_blank',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );
        $isJumbleSentence = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.jumble_sentence',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );
        $isFillText = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.fill_text',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );
        $isMatching = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.matching',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );
        $isOrdering = $questionManageClass->checkCurrentQuestionType(
            configFromModule('panel.question_types.ordering',ApplicationModules::QUESTION_BANK_MODULE_NAME)
        );

        return [
            $isMultiChoice,
            $isTrueFalse,
            $isFillInBlank,
            $isJumbleSentence,
            $isFillText,
            $isMatching,
            $isOrdering
        ];
    }


    public static function checkCanShareWithLibrary(QuestionBank $questionBank){
        if($questionBank->isShared())
            throw new ErrorMsgException(
                transMsg('this_question_has_been_shared_before',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            );

    }

    public static function checkValidShareTypeWithMyAccount(QuestionBank $questionBank,string $share_type){
        if($share_type == configFromModule('panel.question_share_types_with_library.school',ApplicationModules::QUESTION_BANK_MODULE_NAME)){
            if(is_null($questionBank->school_id) && is_null($questionBank->teacher_id))
                throw new ErrorMsgException('invalid share type with your account type');
        }

        if($share_type == configFromModule('panel.question_share_types_with_library.my_private_student',ApplicationModules::QUESTION_BANK_MODULE_NAME)){
            if(is_null($questionBank->educator_id))
                throw new ErrorMsgException('invalid share type with your account type');
        }

    }

}
