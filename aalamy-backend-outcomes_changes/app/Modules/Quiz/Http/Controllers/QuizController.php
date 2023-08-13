<?php

namespace Modules\Quiz\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\Quiz\SendNewQuizNotification;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\QuestionByAccountTypeManagementFactory;
use Modules\Quiz\Http\Controllers\Classes\GenerateQuizClass;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\QuizManagementFactory;
use Modules\Quiz\Http\Controllers\Classes\ManageQuiz\StudentQuiz;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Http\DTO\ManuallyQuizData;
use Modules\Quiz\Http\DTO\QuizData;
use Modules\Quiz\Http\Requests\Quiz\CreateNewQuizManuallyRequest;
use Modules\Quiz\Http\Requests\Quiz\DestroyQuizRequest;
use Modules\Quiz\Http\Requests\Quiz\GenerateRandomQuizRequest;
use Modules\Quiz\Http\Requests\Quiz\GetAllComingQuizzesRequest;
use Modules\Quiz\Http\Requests\Quiz\GetAllMyQuizzesWithMarkRequest;
use Modules\Quiz\Http\Requests\Quiz\GetByRosterIdForGenerateGradeBookRequest;
use Modules\Quiz\Http\Requests\Quiz\GetComingQuizzesByRosterIdRequest;
use Modules\Quiz\Http\Requests\Quiz\GetMyQuizzesWithMarkByRosterIdRequest;
use Modules\Quiz\Http\Requests\Quiz\GetQuizInfoForStudentRequest;
use Modules\Quiz\Http\Requests\Quiz\GetQuizzesByRosterIdRequest;
use Modules\Quiz\Http\Requests\Quiz\ShowQuizForOwnerRequest;
use Modules\Quiz\Http\Requests\Quiz\ShowQuizForStudentRequest;
use Modules\Quiz\Http\Resources\MyFinishedQuizzesWithMarksResource;
use Modules\Quiz\Http\Resources\OwnerResources\QuizResourceWithOriginalSolutionResource;
use Modules\Quiz\Http\Resources\QuizResource;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizLesson;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizStudent;
use Modules\Quiz\Models\QuizUnit;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\StudentRosterClass;
use Modules\User\Http\Controllers\Classes\UserServices;

class QuizController extends Controller
{



    public function getComingQuizzesByRosterId(GetComingQuizzesByRosterIdRequest $request,$roster_id){

        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);

        $studentQuiz = new StudentQuiz($student);
        $quizzes = $studentQuiz->getComingQuizzesByRosterId($roster_id);
        return ApiResponseClass::successResponse(QuizResource::collection($quizzes));

    }

    public function getAllMyComingQuizzes(GetAllComingQuizzesRequest $request){

        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);

        $studentQuiz = new StudentQuiz($student);
        $quizzes = $studentQuiz->getComingQuizzesAll();
        return ApiResponseClass::successResponse(QuizResource::collection($quizzes));

    }


    public function getMyFinishedStudentQuizzesWithMarksByRosterId(GetMyQuizzesWithMarkByRosterIdRequest $request,$roster_id){
        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);


        $myQuizzesWithMarks = Quiz::withoutGlobalScopes()
            ->where('quizzes.roster_id',$roster_id)
            ->where('quizzes.deleted',false)
            ->leftjoin('quiz_students',function ($join){
                $join->on('quiz_students.quiz_id','quizzes.id');
                $join->where('quiz_students.deleted',false);
            })
            ->leftjoin('quiz_questions',function ($join){
                $join->on('quiz_questions.quiz_id','quizzes.id');
                $join->where('quiz_questions.deleted',false);
            })
            ->leftjoin('quiz_question_student_answers',function ($join)use($student){
                $join->on('quiz_question_student_answers.quiz_question_id','quiz_questions.id');

                $join->on('quiz_question_student_answers.quiz_student_id','quiz_students.id');
                $join->where('quiz_question_student_answers.student_id',$student->id);
                $join->where('quiz_question_student_answers.deleted',false);

            })
            ->where(function ($query){
                return $query->where('quizzes.end_date','<',Carbon::now())
                    ->orWhereNotNull('quiz_students.end_date');
            })
            ->distinct()
            ->select(DB::raw('Sum(quiz_question_student_answers.mark) as full_mark'),
                'quizzes.mark as quiz_mark',
                'quizzes.name',
                'quizzes.start_date',
                'quizzes.end_date',
                'quizzes.time',
                'quizzes.id as quiz_id')
            ->groupBy(
                'quiz_mark',
                'quizzes.name',
                'start_date',
                'end_date',
                'time',
                'quizzes.id')
            ->orderBy('quiz_id','desc')
            ->get();


        return  ApiResponseClass::successResponse(MyFinishedQuizzesWithMarksResource::collection($myQuizzesWithMarks));
    }


    public function getMyFinishedStudentQuizzesWithMarksAll(GetAllMyQuizzesWithMarkRequest $request){
        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);

        $rosterManagmentClass = new StudentRosterClass($student);
        $myRosterIds = $rosterManagmentClass->myRosters()->pluck('id')->toArray();

        $myQuizzesWithMarks = Quiz::withoutGlobalScopes()
            ->whereIn('quizzes.roster_id',$myRosterIds)
            ->where('quizzes.deleted',false)
            ->leftjoin('level_subjects','level_subjects.id','quizzes.level_subject_id')
            ->leftjoin('levels','levels.id','level_subjects.level_id')
            ->leftjoin('subjects','subjects.id','level_subjects.subject_id')
            ->leftjoin('units','units.id','quizzes.unit_id')
            ->leftjoin('lessons','lessons.id','quizzes.lesson_id')
            ->leftjoin('educators','educators.id','quizzes.educator_id')
            ->leftjoin('users as educator_user','educator_user.id','educators.user_id')
            ->leftjoin('teachers','teachers.id','quizzes.teacher_id')
            ->leftjoin('users as teacher_user','teacher_user.id','teachers.user_id')
            ->leftjoin('schools','schools.id','quizzes.school_id')
            ->leftjoin('quiz_students',function ($join){
                $join->on('quiz_students.quiz_id','quizzes.id');
                $join->where('quiz_students.deleted',false);
            })
            ->leftjoin('quiz_questions',function ($join){
                $join->on('quiz_questions.quiz_id','quizzes.id');
                $join->where('quiz_questions.deleted',false);
            })
            ->leftjoin('quiz_question_student_answers',function ($join)use($student){
                $join->on('quiz_question_student_answers.quiz_question_id','quiz_questions.id');

                $join->on('quiz_question_student_answers.quiz_student_id','quiz_students.id');
                $join->where('quiz_question_student_answers.student_id',$student->id);
                $join->where('quiz_question_student_answers.deleted',false);

            })
            ->where(function ($query){
                return $query->where('quizzes.end_date','<',Carbon::now())
                    /*->orWhereNotNull('quiz_students.end_date')*/;
            })
            ->distinct()
            ->select(DB::raw('Sum(quiz_question_student_answers.mark) as full_mark'),
                'quizzes.mark as quiz_mark',
                'quizzes.name',
                'quizzes.prevent_display_answers',
                'quizzes.start_date',
                'quizzes.end_date',
                'quizzes.time',
                'levels.name as level_name',
                'subjects.name as subject_name',
                'units.name as unit_name',
                'lessons.name as lesson_name',
                'educator_user.fname as owner_fname',
                'educator_user.fname as owner_lname',
                'schools.school_name as school_name',
                'quizzes.id as quiz_id')
            ->groupBy(
                'quiz_mark',
                'quizzes.name',
                'quizzes.prevent_display_answers',
                'start_date',
                'end_date',
                'time',
                'level_name',
                'subject_name',
                'unit_name',
                'lesson_name',
                'owner_fname',
                'owner_lname',
                'school_name',
                'quizzes.id')
            ->orderBy('quiz_id','desc')
            ->get();

        return  ApiResponseClass::successResponse(MyFinishedQuizzesWithMarksResource::collection($myQuizzesWithMarks));
    }


    /**
     * if the student doesn't press on end quiz then couldn't show that quiz here until end_date done
     */
    public function getByRosterId(GetQuizzesByRosterIdRequest $request,$roster_id){
        $user = $request->user();
        $quizClass = QuizManagementFactory::createForDisplay($user);
        $quizzes = $quizClass
            ->getMyQuizzesByRosterIdAll($roster_id);
        return ApiResponseClass::successResponse(QuizResource::collection($quizzes));
    }

    public function getByRosterIdForGenerateGradeBook(GetByRosterIdForGenerateGradeBookRequest $request,$roster_id){
        $user = $request->user();
        $quizClass = QuizManagementFactory::create($user);
        $quizzes = $quizClass
            ->setFilter(FilterQuizData::fromRequest($request))
            ->getMyEndedQuizzesByRosterIdAll($roster_id);
        return ApiResponseClass::successResponse(QuizResource::collection($quizzes));
    }

    public function createNewQuizManually(CreateNewQuizManuallyRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $quizData = ManuallyQuizData::fromRequest($request);
        $quiz = Quiz::create($quizData->all());

        $arrayForCreate=[];
        $quizMark = 0;
        foreach ($quizData->questions as $questionObject){
            $quizMark+=$questionObject['mark'];
            $arrayForCreate[] = [
                'question_id' => $questionObject['question_id'],
                'quiz_id' => $quiz->id,
                'mark' => $questionObject['mark'],
                'created_at' => Carbon::now(),
            ];
        }
        $defaultQuizMark = configFromModule('panel.quiz_full_mark',ApplicationModules::QUIZ_MODULE_NAME);
        if(round($quizMark,2)!= $defaultQuizMark)
            throw new ErrorMsgException("the quiz mark should be $defaultQuizMark");

        QuizQuestion::insert($arrayForCreate);

        DB::commit();
        ServicesClass::dispatchJob(new SendNewQuizNotification($quiz,$user));
        return ApiResponseClass::successMsgResponse();

    }

    public function generateRandomQuiz(GenerateRandomQuizRequest $request){

/*
//        $q = round(16.666666666667,2);
        $q = 16.666666666667;
//        $q = (string) (50) / 3;
//        $sum = (string) (($q*100)+($q*100)+($q*100))/100;
        $sum = ($q+$q+$q);
        return round($sum,2);

        $r = ((0.7)+(0.1))*10;
        if((((0.7)+(0.1))*10*100)=== 8*100)
            return 'true';
        return 'false';

return (string)100.01;*/
        $user = $request->user();
        DB::beginTransaction();
        $quizData = QuizData::fromRequest($request);
        $quiz = Quiz::create($quizData->all());
        foreach ($quizData->unit_ids as $unit_id){
            QuizUnit::create([
                'quiz_id' => $quiz->id,
                'unit_id' => $unit_id,
            ]);

        }

        foreach ($quizData->lesson_ids as $lesson_id){
            QuizLesson::create([
                'quiz_id' => $quiz->id,
                'lesson_id' => $lesson_id,
            ]);

        }


        $questionBankClass = QuestionByAccountTypeManagementFactory::create($user);
        $generateQuizClass = new GenerateQuizClass($quizData,$questionBankClass);
        $questionsBank = $generateQuizClass->generate();
        $arrayForCreate = $generateQuizClass->prepareQuizQuestionsArrayForCreate($questionsBank,$quiz);

        QuizQuestion::insert($arrayForCreate);
//        $quiz->load(['Questions.QuestionBank'=>function($query){
//            return $query->withAllQuestionTypes();
//        }]);

        DB::commit();
        ServicesClass::dispatchJob(new SendNewQuizNotification($quiz,$user));
//        return ApiResponseClass::successResponse($quiz);
        return ApiResponseClass::successMsgResponse();
    }


    public function showForOwner(ShowQuizForOwnerRequest $request,$id){
        $quiz = $request->getQuiz();
        $quiz->load(['Questions.QuestionBank'=>function($query){
            return $query->withAllQuestionTypes();
        }]);

//        return ApiResponseClass::successResponse(new QuizResource($quiz,true));
        return ApiResponseClass::successResponse(new QuizResourceWithOriginalSolutionResource($quiz));
    }


    public function showForStudent(ShowQuizForStudentRequest $request,$id){
        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);
//        $user->load('Student');

        $quizStudent = QuizStudent::where('student_id',$student->id)
            ->where('quiz_id',$id)
            ->first();
        $studentStartedTheQuizAt = isset($quizStudent)?$quizStudent->start_date:null;

        $quiz = $request->getQuiz();
        if(is_null($studentStartedTheQuizAt)){
            $quiz->load(['Questions.QuestionBank'=>function($query){
                return $query->withAllQuestionTypesForStudentQuiz();
            }]);
        }else{
            $startedAt = Carbon::createFromFormat(
                'Y-m-d'.' '.'H:i:s',$studentStartedTheQuizAt
            );
            $startedAtTimestamp = $startedAt->addMinutes($quiz->time)->timestamp;
            $now = Carbon::now();
            $nowTimestamp = $now->timestamp;
            $restTimeInSeconds = $startedAtTimestamp - $nowTimestamp;

            if($restTimeInSeconds<=0)
                throw new ErrorMsgException('time over');

            $quizEndDatTimestamp = Carbon::createFromFormat(
                'Y-m-d'.' '.'H:i:s',$quiz->end_date
            )->timestamp;
            $restTimeToExpiredQuizInSeconds = $quizEndDatTimestamp - $nowTimestamp;

            if($restTimeToExpiredQuizInSeconds < $restTimeInSeconds)
                $restTimeInSeconds = $restTimeToExpiredQuizInSeconds;



            $quiz->load(['Questions'=>function($query)use($user,$student){
                return $query->with(['QuestionBank'=>function($query)use($user){
                    return $query->withAllQuestionTypesForStudentQuiz();
                },'QuizQuestionStudentAnswers'=>function($query)use($student){
                    return $query->where('student_id',$student->id)
                        ->withAllQuestionAnswersType();
                }]);
            }]);
        }

        return ApiResponseClass::successResponse([
            'quiz' => new QuizResource($quiz),
            'time_after_student_start' => isset($studentStartedTheQuizAt)
//                ?$quiz->time - $studentStartedByMinutes
                ?$restTimeInSeconds
                :null
        ]);


    }

    public function getQuizInfoForStudent(GetQuizInfoForStudentRequest $request,$id){
        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);

        $quizStudent = QuizStudent::where('student_id',$student->id)
            ->where('quiz_id',$id)
            ->first();

        $quiz = $request->getQuiz()->load([
            'Educator.User','School','Teacher.User'
        ]);

        return ApiResponseClass::successResponse([
            'quiz' => new QuizResource($quiz),
            'quiz_student_details' => $quizStudent
        ]);
    }



    public function softDelete(DestroyQuizRequest $request,$id){
        DB::beginTransaction();
        $quiz = $request->getQuiz();
        $quiz->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }







}
