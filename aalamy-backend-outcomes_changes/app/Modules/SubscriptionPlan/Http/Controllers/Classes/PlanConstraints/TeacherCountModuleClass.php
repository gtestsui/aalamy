<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints;



use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\SubscriptionPlan\Models\Module;
use Modules\User\Http\Controllers\Classes\AccountDetails\SchoolDetailsClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\User;

class TeacherCountModuleClass extends BasePlanConstraintsClass
{

    protected School $school;


    /**
     * owner here is school always because this feature available for only schools
     * @param User $user
     * @return static
     */
    public static function createByOwner(User $user)
    {
        $static = new static(true);
        $static->user = $user;
        list(,$school) = UserServices::getAccountTypeAndObject($user);

        $static->module = Module::where(
                'identify',getModuleIdentify('teachers_count')
            )
            ->where('type','school')
            ->firstOrFail();

        $static->school = $school;
        return $static;
    }


    /**
     * other here are teachers (when teacher trying to accept enroll schoolRequest or trying to send request)
     * @param User $schoolUser(the owner of the plan schoolUser)
     * @param School $school of the owner
     * @return static
     */
    public static function createByOther(User $schoolUser,School $school)
    {
        $static = new static(true);
        $static->createdByOther = true;
        $static->user = $schoolUser;
        $static->school = $school;

        $static->module = Module::where(
                'identify',getModuleIdentify('teachers_count')
            )
            ->where('type','school')
            ->firstOrFail();

        return $static;
    }


    /**
     * check if the teachers count in the school reached to the limit of the plan
     * @return bool
     *
     * @throws ModelNotFoundException
     * @throws ErrorMsgException
     */
    public function canAddMoreTeachers(){
        $this->checkAndSetUserSubscribe();

        $availabeQuantityFromUserPlan = $this->getAvailabeQuantity();

        $teachersCount = $this->getCurrentTeachersCount();

        if($teachersCount >= $availabeQuantityFromUserPlan)
            return false;

        return true;
    }


    public function check(){

        if($this->canAddMoreTeachers())
            return true;

        throw new ErrorMsgException(transMsg(
            'you_have_reached_to_limit_of_your_subscription',
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME
        ));

    }

    /**
     * return customized message when the educator send request to enroll to school,or approve request,...
     * @param string|null $msg
     * @throws  ErrorMsgException
     * max_count_of_teachers
     */
    public function checkWithCustomizedErrorForTeacher($msg=null){

        if($this->canAddMoreTeachers())
            return true;

        $msg = $msg ?? transMsg('max_count_of_teachers',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);

        throw new ErrorMsgException($msg);

    }



    /**
     * @return int
     */
    protected function getCurrentTeachersCount(){
        $schoolDetails = new SchoolDetailsClass($this->school);
        return  $schoolDetails->myTeachersCount();

    }




}
