<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Modules\User\Http\DTO\EducatorData;
use Illuminate\Http\Request;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\SchoolInvitation\Models\SchoolTeacherInvitation;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\TeacherCountModuleByTeachersClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\TeacherCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class EducatorClass extends UserClass
{

    public function getDataFromRequest(Request $request,UserData $userData=null):EducatorData
    {
        $data = EducatorData::fromRequest($request,$userData);
        return $data;
    }

    /**
     * when the educator create his account so we by default will create
     * a free plan
     */
    public function create(EducatorData $educatorData, UserData $userData):User
    {
        $withObserve = false;
        if(is_null($educatorData->created_by_school_id))
            $withObserve = true;
        $user = Parent::createUser($userData,$withObserve);

        $educator = Educator::create([
            'user_id' => $user->id,
            'bio' => $educatorData->bio,
            'certificate' => $educatorData->certificate,
        ]);

        $userSubscribeClass = new UserSubscribeClass($user);
        $userSubscribeClass->subscribeEducatorFreePlan();

        //we use school id if the user had received an invitation then should sign and send the school id
        if(isset($educatorData->school_id) || isset($educatorData->created_by_school_id)){
            $schoolId = $educatorData->created_by_school_id;
            if(isset($educatorData->school_id)){
                $schoolId = $educatorData->school_id;

                $schoolInvite = SchoolTeacherInvitation::where('school_id',$educatorData->school_id)
                    ->where('teacher_email',$userData->email)
                    ->first();

                if(is_null($schoolInvite))
                    throw new ErrorMsgException(transMsg('you_dont_have_an_previous_invitation',ApplicationModules::USER_MODULE_NAME) );

            }


            //check teachers count in school plan and customize the error msg
            $school = School::with('User')->findOrFail($schoolId);
            $teacherCountModuleClass = TeacherCountModuleClass::createByOther($school->User,$school);
            $teacherCountModuleClass->checkWithCustomizedErrorForTeacher();

            Teacher::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'bio' => $educatorData->bio,
            ]);

            //here we can send notification to school
        }

        $user->load('Educator');
        LevelServices::createDefaultEducationalContent($user);


        return $user;
    }

    public function update(EducatorData $educatorData,User $user):User
    {
        $user->load('Educator');

        $educator = $user->Educator;
        $educatorArrayForUpdate = $educatorData->initializeForUpdate($educatorData);
        $educator->update($educatorArrayForUpdate);
//        $user->refresh();
        return $user;
    }

    public function updateAccountWithPersonalInfo(EducatorData $educatorData, UserData $userData,User $user):User
    {
        $user = Parent::updateUser($userData,$user);
        $user = $this->update($educatorData,$user);
        return  $user;
    }
}
