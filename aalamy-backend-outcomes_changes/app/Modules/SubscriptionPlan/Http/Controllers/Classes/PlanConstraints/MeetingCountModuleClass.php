<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints;



use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Meeting\Models\Meeting;
use Modules\SubscriptionPlan\Models\Module;
use Modules\User\Http\Controllers\Classes\AccountDetails\AccountDetailsFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class MeetingCountModuleClass extends BasePlanConstraintsClass
{

    /**
     * we have used $accountType and $accountObject
     * because we have educator and school have this feature
     * (to make it dynamic way)
     */


    /**
     * @var string
     */
    protected string $accountType;
    /**
     * @var School|Educator
     */
    protected  $accountObject;



//    /**
//     * to prevent call this class using new keyword (new AssignmentCountModuleClass());
//     */
//    public function __construct($calledStatic=false) {
//        if(!$calledStatic)
//        throw new ErrorMsgException('cant initialize class '.class_basename($this).' use createByOwner or createByOther');
//    }

    /**
     * the owner is school or educator
     * we have initialized tow constructor (byOwner and byStudent)
     * because when the user who logged in is student then
     * UserServices::getAccountTypeAndObject will return object of student(singleton)
     * @param User $user (the owner of the plan)
     */
    public static function createByOwner(User $user)
    {
        $static = new static(true);

        $static->user = $user;

        list($static->accountType,$static->accountObject) = UserServices::getAccountTypeAndObject($user);

        $static->module = Module::where(
                'identify',getModuleIdentify('meetings_count')
            )
            ->where('type',$static->accountType)
            ->firstOrFail();
        return $static;
    }

    /**
     * other maybe teacher or student (if teacher have permission
     * and when student trying to accept enroll schoolRequest or trying to send request)
     * @param User $user(the owner of the plan schoolUser or educatorUser)
     * @param Educator|School $accountObject of the owner
     * @return static
     */
    public static function createByOther(User $ownerUser,$accountObject)
    {
        $static = new static(true);

        $static->createdByOther = true;
        $static->user = $ownerUser;
        $static->accountObject = $accountObject;
        $static->accountType = $ownerUser->account_type;

        $static->module = Module::where(
                'identify',getModuleIdentify('meetings_count')
            )
            ->where('type',$static->accountType)
            ->firstOrFail();
        return $static;
    }


    /**
     * @return bool
     * @throws ErrorMsgException
     * @throws ModelNotFoundException
     */
    public function canAddMoreMeetings(){
        $this->checkAndSetUserSubscribe();
        $availableQuantityFromUserPlan = $this->getAvailabeQuantity();
        $currentCount = $this->getCurrentMeetingsCount();
        if($currentCount >= $availableQuantityFromUserPlan)
            return false;

        return true;
    }





    /**
     * @throws ErrorMsgException
     * @return bool
     */
    public function check(){

        if($this->createdByOther)
            return $this->checkWithCustomizedErrorForTeacher();

        if($this->canAddMoreMeetings())
            return true;

        throw new ErrorMsgException(transMsg(
            'you_have_reached_to_limit_of_your_subscription',
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME
        ));

    }

    /**
     * when teacher trying to add inside his school
     * @param string|null $msg
     * @throws ErrorMsgException
     * @return bool
     */
    public function checkWithCustomizedErrorForTeacher($msg=null){

        if($this->canAddMoreMeetings())
            return true;

        $msg = $msg ?? transMsg('max_count_of_school_meetings',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);
        throw new ErrorMsgException($msg);

    }




    /**
     * @return int
     */
    protected function getCurrentMeetingsCount(){
        return Meeting::where($this->accountType.'_id',$this->accountObject->id)
            ->count();

    }




}
