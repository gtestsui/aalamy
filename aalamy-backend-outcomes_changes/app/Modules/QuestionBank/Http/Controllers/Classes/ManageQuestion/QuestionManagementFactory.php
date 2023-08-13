<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\QuestionBank\Http\Requests\FillInBlank\StoreQuestionBankFillInBlankRequest;
use Modules\QuestionBank\Http\Requests\FillText\StoreQuestionBankFillTextRequest;
use Modules\QuestionBank\Http\Requests\JumbleSentence\StoreQuestionBankJumbleSentenceRequest;
use Modules\QuestionBank\Http\Requests\Matching\StoreQuestionBankMatchingRequest;
use Modules\QuestionBank\Http\Requests\MultiChoice\StoreQuestionBankMultiChoiceRequest;
use Modules\QuestionBank\Http\Requests\Ordering\StoreQuestionBankOrderingRequest;
use Modules\QuestionBank\Http\Requests\TrueFalse\StoreQuestionBankTrueFalseRequest;

abstract class QuestionManagementFactory /*extends AbstractManagementFactory*/
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
            => MultiChoiceClassManagement::class,

            configFromModule('panel.question_types.true_false',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => TrueFalseClassManagement::class,

            configFromModule('panel.question_types.fill_in_blank',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => FillInBlankClassManagement::class,

            configFromModule('panel.question_types.fill_text',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => FillTextClassManagement::class,

            configFromModule('panel.question_types.matching',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => MatchingClassManagement::class,

            configFromModule('panel.question_types.ordering',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => OrderingClassManagement::class,

            configFromModule('panel.question_types.jumble_sentence',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => JumbleSentenceClassManagement::class,

        ];

        if(!key_exists($questionType,$supportedQuestionTypes))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = $supportedQuestionTypes[$questionType];
        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


    public static function createStoreValidationClass(string $questionType){
        $supportedQuestionTypes = [

            configFromModule('panel.question_types.multi_choice',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankMultiChoiceRequest::class,

            configFromModule('panel.question_types.true_false',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankTrueFalseRequest::class,

            configFromModule('panel.question_types.fill_in_blank',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankFillInBlankRequest::class,

            configFromModule('panel.question_types.fill_text',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankFillTextRequest::class,

            configFromModule('panel.question_types.matching',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankMatchingRequest::class,

            configFromModule('panel.question_types.ordering',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankOrderingRequest::class,

            configFromModule('panel.question_types.jumble_sentence',ApplicationModules::QUESTION_BANK_MODULE_NAME)
            => StoreQuestionBankJumbleSentenceRequest::class,

        ];

        if(!key_exists($questionType,$supportedQuestionTypes))
            throw new ErrorMsgException('trying to declare invalid validation class type ');

        $classPath = $supportedQuestionTypes[$questionType];

        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}
