<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByFileType;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Assignment\Models\Assignment;
use Modules\LearningResource\Http\DTO\LearningResourceData;

abstract class GeneratedAssignmentManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'pdf' => AssignmentLearningResourcePdf::class,
    ];

    /**
     * return the all array or just item from it
     */
    public static function supportedClasses($key=null){

        return isset($key)
            ?self::$paths[$key]
            :self::$paths;
    }

    public static function create(Assignment $assignment):GeneratedAssignmentTypeInterface
    {
        $generatedAssignmentType = configFromModule('panel.generate_assignment_file_type_default',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME);

        //we made this to check on teacher type ,and we have not sent $teacherId because we don't need him here

        if(!key_exists($generatedAssignmentType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$generatedAssignmentType];
        if(class_exists($classPath)){
            return new $classPath($assignment);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }




}
