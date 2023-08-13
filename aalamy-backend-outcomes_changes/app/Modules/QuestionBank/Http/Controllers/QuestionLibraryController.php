<?php

namespace Modules\QuestionBank\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion\QuestionManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyAllowedQuestionLibrary\MyAllowedQuestionLibraryByAccountTypeManagementFactory;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyOwnQuestionLibrary\MyOwnQuestionLibraryByAccountTypeManagementFactory;
use Modules\QuestionBank\Http\Requests\QuestionLibrary\GetMyQuestionLibraryRequest;
use Modules\QuestionBank\Http\Requests\QuestionLibrary\ShowQuestionLibraryRequest;
use Modules\QuestionBank\Http\Requests\QuestionLibrary\UpdateQuestionLibraryRequest;
use Modules\QuestionBank\Http\Resources\QuestionLibrary\QuestionLibraryResource;
use Modules\QuestionBank\Models\LibraryQuestion;

class QuestionLibraryController extends Controller
{


    /**
     * @see LibraryQuestion scopeFilterMyQuestionLibrary function
     * @param GetMyQuestionLibraryRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ErrorMsgException
     */
    public function getMyQuestionPaginate(GetMyQuestionLibraryRequest $request){
        $user = $request->user();
        $questionManagemnet = MyOwnQuestionLibraryByAccountTypeManagementFactory::create($user,$request->my_teacher_id);
        $myQuestionBanks = $questionManagemnet->getMyQuestionLibraryPaginate($request->filter);
        return ApiResponseClass::successResponse(QuestionLibraryResource::collection($myQuestionBanks));
    }


     /**
      * @see LibraryQuestion scopeFilterMyQuestionLibrary function
      */
    public function getMyAllowedQuestionPaginate(GetMyQuestionLibraryRequest $request){
        $user = $request->user();
        $questionManagemnet = MyAllowedQuestionLibraryByAccountTypeManagementFactory::create($user,$request->my_teacher_id);
        $myQuestionBanks = $questionManagemnet->getMyAllowedQuestionLibraryPaginate($request->filter);
        return ApiResponseClass::successResponse(QuestionLibraryResource::collection($myQuestionBanks));
    }

    public function show(ShowQuestionLibraryRequest $request,$id){
        $questionLibrary = $request->getQuestionLibrary();
        $questionClassByType = QuestionManagementFactory::create($questionLibrary->question_type);
        $questionLibrary = $questionClassByType->load($questionLibrary);
        return ApiResponseClass::successResponse(new QuestionLibraryResource($questionLibrary));
    }

    public function update(UpdateQuestionLibraryRequest $request,$id){
        $questionLibrary = $request->getQuestionLibrary();
        $questionLibrary->update([
            'share_type' => $request->share_type
        ]);
        return ApiResponseClass::successResponse(new QuestionLibraryResource($questionLibrary));

    }



}
