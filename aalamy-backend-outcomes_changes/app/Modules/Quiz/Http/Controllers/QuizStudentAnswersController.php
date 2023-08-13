<?php

namespace Modules\Quiz\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Http\Controllers\Classes\ManageQuizQuestionStudentAnswer\QuizQuestionAnswerManagementFactory;
use Modules\Quiz\Http\Requests\QuizStudentAnswer\EndQuizRequest;
use Modules\Quiz\Http\Requests\QuizStudentAnswer\GetStudentsMarksByQuizIdRequest;
use Modules\Quiz\Http\Requests\QuizStudentAnswer\ShowStudentQuizAnswersRequest;
use Modules\Quiz\Http\Requests\QuizStudentAnswer\StartQuizRequest;
use Modules\Quiz\Http\Requests\QuizStudentAnswer\StoreQuizAnswerRequest;
use Modules\Quiz\Http\Resources\GetStudentsMarksResource;
use Modules\Quiz\Http\Resources\OwnerResources\QuizResourceWithOriginalSolutionResource;
use Modules\Quiz\Http\Resources\OwnerResources\QuizResourceWithoutOriginalSolutionResource;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;
use Modules\Quiz\Models\QuizStudent;
use Modules\Roster\Models\RosterStudent;
use Modules\User\Http\Controllers\Classes\UserServices;

class QuizStudentAnswersController extends Controller
{

    public function getStudentsMarksByQuizId(GetStudentsMarksByQuizIdRequest $request,$quiz_id){

        $studentMarks = Quiz::withoutGlobalScopes()
            ->where('quizzes.id',$quiz_id)
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

    public function startQuiz(StartQuizRequest $request,$quiz_id){
        $user = $request->user();
        list(,$student)=UserServices::getAccountTypeAndObject($user);
        $quizStudent = QuizStudent::where('student_id',$student->id)
            ->where('quiz_id',$quiz_id)
            ->first();
        if(is_null($quizStudent))
            QuizStudent::create([
                'student_id' => $student->id,
                'quiz_id' => $quiz_id,
                'start_date' => Carbon::now()
            ]);
        return ApiResponseClass::successMsgResponse();
    }

    public function endQuiz(EndQuizRequest $request,$quiz_id){
        $user = $request->user();
        list(,$student)=UserServices::getAccountTypeAndObject($user);
        $quizStudent = QuizStudent::where('student_id',$student->id)
            ->where('quiz_id',$quiz_id)
            ->first();
        $quizStudent->update([
            'end_date' => Carbon::now()
        ]);
        return ApiResponseClass::successMsgResponse();
    }

    public function storeOrUpate(StoreQuizAnswerRequest $request,$quiz_id){
        DB::beginTransaction();
        $user = $request->user();
        list(,$student)=UserServices::getAccountTypeAndObject($user);


        $quizQuestionIds = array_column($request->answers,'quiz_question_id');

        $quizQuestions = QuizQuestion::whereIn('id',$quizQuestionIds)
            ->where('quiz_id',$quiz_id)
            ->with(['QuestionBank'=>function($query){
                return $query->withAllQuestionTypes();
            }])
            ->get();

        $quizStudent = QuizStudent::where('quiz_id',$quiz_id)
            ->where('student_id',$student->id)
            ->firstOrFail();

//        $studentFullMark = 0;
        foreach ($request->answers as $answerObject){

            $quizQuestion = $quizQuestions->where('id',$answerObject['quiz_question_id'])->first();

            $quizQuestionStudentAnswer = QuizQuestionStudentAnswer::where('quiz_question_id',$quizQuestion->id)
                ->where('student_id',$student->id)
                ->first();
            if(is_null($quizQuestionStudentAnswer)){
                $quizQuestionStudentAnswer = QuizQuestionStudentAnswer::create([
                    'quiz_question_id' => $quizQuestion->id,
                    'student_id' => $student->id,
                    'quiz_student_id' => $quizStudent->id,
                ]);
            }


            $quizQuestionAnswerClass = QuizQuestionAnswerManagementFactory::create($quizQuestion->QuestionBank->question_type);
            $quizQuestionAnswerClass->checkAnswer($quizQuestion,$answerObject,$quizQuestionStudentAnswer);
//            $studentFullMark+=$quizQuestionStudentAnswer->mark;
        }

        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }


    public function showStudentQuizAnswers(ShowStudentQuizAnswersRequest $request,$quiz_id,$student_id){
        $user = $request->user();

        $finalMark = 0;
        $quiz = Quiz::with(['Questions'=>function($query)use($student_id){
            return $query->with(['QuestionBank'=>function($query)use($student_id){
                return $query->withAllQuestionTypesForStudentQuiz();
            },'QuizQuestionStudentAnswers'=>function($query)use($student_id){
                return $query->where('student_id',$student_id)
                    ->withAllQuestionAnswersType();
            }]);
        }])
            ->findOrFail($quiz_id);

        foreach ($quiz->Questions as $question){
            $finalMark += isset($question->QuizQuestionStudentAnswers[0])?$question->QuizQuestionStudentAnswers[0]->mark:0;
        }

        return ApiResponseClass::successResponse([
            'final_mark' => $finalMark,
            'quiz'=>new QuizResourceWithoutOriginalSolutionResource($quiz),
        ]);

    }


}
