<?php


namespace Modules\User\Http\Controllers\Classes;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\User\Models\Student;

class TargetStudentByParentSingleton
{

    public static ?Student $student = null;

    /** call this method to get instance to prevent running new query while get student */
    /**
     * @return Student
     * @throws ModelNotFoundException
     */
    public static function instance($targetedStudentId){

        if (static::$student === null){
            static::$student = Student::findOrFail($targetedStudentId);

        }

        return static::$student;


    }

    /** protected to prevent cloning */
    protected function __clone(){
    }

    /** protected to prevent instantiation from outside of the class */
    protected function __construct(){
    }
}
