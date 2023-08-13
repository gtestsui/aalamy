<?php

namespace Modules\QuestionBank\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion\QuestionManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\QuestionByAccountTypeManagementFactory;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Http\Requests\Question\DestroyQuestionBankRequest;
use Modules\QuestionBank\Http\Requests\Question\GetMyQuestionBankRequest;
use Modules\QuestionBank\Http\Requests\Question\GetQuestionsBankByIdsRequest;
use Modules\QuestionBank\Http\Requests\Question\ShareQuestionBankRequest;
use Modules\QuestionBank\Http\Requests\Question\ShowQuestionRequest;
use Modules\QuestionBank\Http\Requests\Question\StoreQuestionBankRequest;
use Modules\QuestionBank\Http\Requests\Question\UpdateQuestionBankRequest;
use Modules\QuestionBank\Http\Resources\QuestionBank\QuestionBankResource;
use Modules\QuestionBank\Models\QuestionBank;

class QuestionBankController extends Controller
{


    /**
     * @see QuestionBank scopeFilterMyQuestionBank function
     * @param GetMyQuestionBankRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ErrorMsgException
     */
    public function getMyQuestionPaginate(GetMyQuestionBankRequest $request){
        $user = $request->user();
        $questionManagement = QuestionByAccountTypeManagementFactory::create($user/*,$request->my_teacher_id*/);
        $myQuestionBanks = $questionManagement->getMyQuestionBankPaginate($request->filter);

        return ApiResponseClass::successResponse(QuestionBankResource::collection($myQuestionBanks));
    }

    public function getQuestionsByIds(GetQuestionsBankByIdsRequest $request){
        $questionsBank = QuestionBank::whereIn('id',$request->ids)
            ->withAllQuestionTypes()
            ->get();
        return ApiResponseClass::successResponse(QuestionBankResource::collection($questionsBank));
    }

    public function show(ShowQuestionRequest $request,$id){
        $user = $request->user();
        $questionBank = $request->getQuestionBank();
        $questionClassByType = QuestionManagementFactory::create($questionBank->question_type);
        $questionBank = $questionClassByType->load($questionBank)
            ->load(['LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            },'Unit','Lesson']);
        return ApiResponseClass::successResponse(new QuestionBankResource($questionBank));
    }

    public function test(GetMyQuestionBankRequest $request){
        $user = $request->user();

        $questionManagemnet = QuestionByAccountTypeManagementFactory::create($user,$request->my_teacher_id);
        $myQuestionBanks = $questionManagemnet->filterMyQuestionBank([
            'search_key' => $request->key,
            'question_type' => $request->question_type,
            'level_subject_id' => $request->level_subject_id,
            'unit_id' => $request->unit_id,
            'lesson_id' => $request->lesson_id,
            'difficult_level' => $request->difficult_levels,
        ]);
        return ApiResponseClass::successResponse(QuestionBankResource::collection($myQuestionBanks));
    }

    public function store(StoreQuestionBankRequest $request){
        DB::beginTransaction();
        $questionData = QuestionBankData::fromRequest($request);
        $question = QuestionBank::create($questionData->all());
        $questionByType = QuestionManagementFactory::create($questionData->question_type);
        $questionByType->createInBank($question,$questionData);
        $question->load(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        },'Unit','Lesson']);
        DB::commit();
        return ApiResponseClass::successResponse(new QuestionBankResource($question));
    }


    /**
     * @note the update function will delete the other related data with questionBank
     * and then reInsert them
     */
    public function update(UpdateQuestionBankRequest $request){
        DB::beginTransaction();
        $questionBank = $request->getQuestionBank();
        $questionData = QuestionBankData::fromRequest($request,$questionBank);
        //update the core question
        $questionBank->update($questionData->initializeForUpdate($questionData));
        //update the question by type
        $questionByType = QuestionManagementFactory::create($questionData->question_type);
        $questionByType->updateInBank($questionBank,$questionData);
        $questionBank->load(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        },'Unit','Lesson']);
        DB::commit();
        return ApiResponseClass::successResponse(new QuestionBankResource($questionBank));
    }

    public function shareWithLibrary(ShareQuestionBankRequest $request,$id){
        DB::beginTransaction();
        $questionBank = $request->getQuestionBank();
        $questionBank->markAsShared();
        //create the question in library
        $questionByType = QuestionManagementFactory::create($questionBank->question_type);
        $questionByType->shareWithLibrary($questionBank,$request->share_type);

        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }

    public function softDelete(DestroyQuestionBankRequest $request, $id){
        DB::beginTransaction();
        $questionBank = $request->getQuestionBank();
        $questionBank->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyQuestionBankRequest $request, $id){
        $questionBank = $request->getQuestionBank();
        $questionBank->delete();
        return ApiResponseClass::deletedResponse();
    }


}
