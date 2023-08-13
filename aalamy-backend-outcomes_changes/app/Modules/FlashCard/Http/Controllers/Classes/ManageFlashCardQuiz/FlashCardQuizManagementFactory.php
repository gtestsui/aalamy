<?php

namespace Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuiz;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\FlashCard\Http\DTO\FlashCardData;
use Modules\FlashCard\Models\FlashCard;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

abstract class FlashCardQuizManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'MultiChoice' => MultiChoiceQuizClass::class,
        'TrueFalse' => TrueFalseQuizClass::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create($quizType,FlashCard $flashCard,FlashCardData $flashCardData)
    {

        if(!key_exists($quizType,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($quizType);
        if(class_exists($classPath)){
            return new $classPath(
                $flashCard,
                $flashCardData->quiz_question_num,
                $flashCardData->quiz_question_choices_num
            );
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}
