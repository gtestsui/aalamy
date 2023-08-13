<?php

namespace Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
abstract class QuizQuestionAnswerManagementFactory /*extends AbstractManagementFactory*/
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
//    protected static $paths = [
//        'multi_choice' => MultiChoiceClassManagement::class,
//    ];

    /**
     * return the all array or just item from it
     */
//    public static function supportedClasses($key=null){
//
//        $paths = [
//            config('QuestionBank.panel.question_types.multi_choice')
//            => MultiChoiceClassManagement::class,
//
//            config('QuestionBank.panel.question_types.true_false')
//            => TrueFalseClassManagement::class,
//        ];
//
//        return isset($key)
//            ?$paths[$key]
//            :$paths;
//    }

    public static function create(string $questionType):BaseManageQuestionAbstract
    {

        $supportedQuestionTypes = [

            configFromModule('panel.question_types.multi_choice',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => MultiChoiceAnswerClassManagement::class,

            configFromModule('panel.question_types.true_false',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => TrueFalseAnswerClassManagement::class,

            configFromModule('panel.question_types.fill_in_blank',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => FillInBlankAnswerClassManagement::class,

            configFromModule('panel.question_types.fill_text',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => FillTextAnswerClassManagement::class,

            configFromModule('panel.question_types.matching',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => MatchingAnswerClassManagement::class,

            configFromModule('panel.question_types.ordering',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => OrderingAnswerClassManagement::class,

            configFromModule('panel.question_types.jumble_sentence',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => JumbleSentenceAnswerClassManagement::class,

        ];

        if(!key_exists($questionType,$supportedQuestionTypes))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = $supportedQuestionTypes[$questionType];
        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


//    public static function createStoreValidationClass(string $questionType){
//        $supportedQuestionTypes = [
//
//            configFromModule('panel.question_types.multi_choice',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankMultiChoiceRequest::class,
//
//            configFromModule('panel.question_types.true_false',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankTrueFalseRequest::class,
//
//            configFromModule('panel.question_types.fill_in_blank',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankFillInBlankRequest::class,
//
//            configFromModule('panel.question_types.fill_text',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankFillTextRequest::class,
//
//            configFromModule('panel.question_types.matching',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankMatchingRequest::class,
//
//            configFromModule('panel.question_types.ordering',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankOrderingRequest::class,
//
//            configFromModule('panel.question_types.jumble_sentence',ApplicationModules::QUESTION_BANK_MODULE_NAME)
//            => StoreQuestionBankJumbleSentenceRequest::class,
//
//        ];
//
//        if(!key_exists($questionType,$supportedQuestionTypes))
//            throw new ErrorMsgException('trying to declare invalid validation class type ');
//
//        $classPath = $supportedQuestionTypes[$questionType];
//
//        if(class_exists($classPath)){
//            return new $classPath();
//        }
//        throw new ErrorMsgException('trying to declare invalid class type ');
//    }


}
