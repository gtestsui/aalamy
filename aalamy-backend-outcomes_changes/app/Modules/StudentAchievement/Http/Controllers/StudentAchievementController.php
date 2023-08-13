<?php

namespace Modules\StudentAchievement\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\Achievement\SendNewAchievementNotification;
use Modules\StudentAchievement\Http\Controllers\Classes\ManageAchievement\StudentAchievementManagementFactory;
use Modules\StudentAchievement\Http\Controllers\Classes\StudentAchievementServices;
use Modules\StudentAchievement\Http\DTO\StudentAchievementData;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\DestroyStudentAchievementRequest;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\GetMyAchievementAsStudentRequest;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\GetMyStudentAchievementWaitingToPublishRequest;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\PublishStudentAchievementRequest;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\StoreStudentAchievementRequest;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\UpdateStudentAchievementRequest;
use Modules\StudentAchievement\Http\Resources\StudentAchievementResource;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Http\Controllers\Classes\UserServices;

class StudentAchievementController extends Controller
{


    public function getMyStudentAchievementWaitingToPublish(GetMyStudentAchievementWaitingToPublishRequest $request){
        $user = $request->user();
        $achievementClass = StudentAchievementManagementFactory::create(
            $user,$request->my_teacher_id
        );
        $studentAchievements = $achievementClass->getMyStudentAchievementWaitingToPublish();
        return ApiResponseClass::successResponse(StudentAchievementResource::collection($studentAchievements));

    }

    public function getMyAchievementsAsStudent(GetMyAchievementAsStudentRequest $request){
        $user = $request->user();
        $user->load('Student');
        $achievements = StudentAchievement::where('student_id',$user->Student->id)
            ->with(['User'])
            ->get();
        return ApiResponseClass::successResponse(StudentAchievementResource::collection($achievements));

    }

    public function show(Request $request,$id){
        $achievement = StudentAchievement::with(['User','Student.User'])->findOrFail($id);
        return ApiResponseClass::successResponse(new StudentAchievementResource($achievement));
    }

    public function store(StoreStudentAchievementRequest $request){
        DB::beginTransaction();
        $user = $request->user();
        $studentAchievementData = StudentAchievementData::fromRequest($request,$user);
        $achievementClass = $request->getAchievemntClass();
        $achievement = $achievementClass->store($studentAchievementData);
        $achievement->load('User');
        DB::commit();
        return ApiResponseClass::successResponse(new StudentAchievementResource($achievement));
    }

    public function publish(PublishStudentAchievementRequest $request,$id){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        $myClassIdsThatContainsTheStudent = $request->getClassIdsThatContainsTheStudent();
        $achievement = $request->getStudentAchievement();
        $achievement->publish($accountType);
        ServicesClass::dispatchJob(
            new SendNewAchievementNotification($myClassIdsThatContainsTheStudent,$achievement)
        );
        return ApiResponseClass::successMsgResponse();
    }

    public function update(UpdateStudentAchievementRequest $request,$id){
        $user = $request->user();
        $studentAchievementData = StudentAchievementData::fromRequest($request,$user);
        $achievement = $request->getStudentAchievement();
        $achievement->update($studentAchievementData->initializeForUpdate($studentAchievementData));
        return ApiResponseClass::successResponse(new StudentAchievementResource($achievement));

    }

    public function destroy(DestroyStudentAchievementRequest $request,$id){
        $achievement = $request->getStudentAchievement();
        StudentAchievementServices::deleteAchievement($achievement);
        return ApiResponseClass::deletedResponse();
    }

}
