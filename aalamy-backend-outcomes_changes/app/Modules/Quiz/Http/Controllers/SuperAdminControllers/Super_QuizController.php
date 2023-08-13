<?php

namespace Modules\Quiz\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Quiz\Http\Resources\GetStudentsMarksResource;
use Modules\Quiz\Http\Resources\QuizResource;
use Modules\Quiz\Models\Quiz;

class Super_QuizController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){

        $quizzes = Quiz::search($request->key,[],[
                'Teacher.User',
                'School',
                'Educator.User',
                'Roster',
                'Unit',
                'Lesson',
                'LevelSubject'=>['Level','Subject']
            ])
            ->trashed($soft_delete)
            ->withCount('QuizStudents')
            ->with([
                'Teacher.User',
                'School',
                'Educator.User',
                'Roster',
                'Unit',
                'Lesson',
                'LevelSubject'=>function($query){
                    return $query->with(['Level','Subject']);
            }])
            ->paginate(config('panel.admin_paginate_num'));

        return ApiResponseClass::successResponse(QuizResource::collection($quizzes));
    }



    public function getStudentsMarksByQuizId(Request $request,$quiz_id){


        $studentMarks = Quiz::withoutGlobalScopes()
            ->where('quizzes.id',$quiz_id)
            ->where('quizzes.deleted',false)
            ->join('quiz_questions',function ($join){
                $join->on('quiz_questions.quiz_id','quizzes.id');
                $join->where('quiz_questions.deleted',false);
            })
            ->leftjoin('quiz_question_student_answers',function ($join){
                $join->on('quiz_question_student_answers.quiz_question_id','quiz_questions.id');
                $join->where('quiz_question_student_answers.deleted',false);
            })
            ->leftjoin('students',function ($join){
                $join->on('students.id','quiz_question_student_answers.student_id');
                $join->where('students.deleted',false);
            })
            ->join('users','students.user_id','users.id')
            ->distinct()
            ->select('quizzes.id as quiz_id',
                'students.id as student_id',
                'users.fname as fname',
                'users.lname as lname',
                DB::raw('Sum(quiz_question_student_answers.mark ) as final_mark')
            )
            ->groupBy('students.id','users.image','quizzes.id','users.fname','users.lname')
            ->get();

        return ApiResponseClass::successResponse(GetStudentsMarksResource::collection($studentMarks));


    }



    public function softDeleteOrRestore(Request $request,$quiz_id){
        DB::beginTransaction();
        $quiz = Quiz::withDeletedItems()
            ->findOrFail($quiz_id);
        $quiz->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new QuizResource($quiz));

    }

    public function destroy(Request $request,$quiz_id){
        DB::beginTransaction();
        $quiz = Quiz::withDeletedItems()
            ->findOrFail($quiz_id);
        $quiz->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
