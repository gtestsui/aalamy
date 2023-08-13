<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints;



use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\SubscriptionPlan\Models\Module;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class MeetingDurationModuleClass extends BasePlanConstraintsClass
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
                'identify',getModuleIdentify('meeting_duration')
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
                'identify',getModuleIdentify('meeting_duration')
            )
            ->where('type',$static->accountType)
            ->firstOrFail();
        return $static;
    }



    /**
     * @return int (by minutes)
     */
    public function getDuration(){
        $this->checkAndSetUserSubscribe();
        return $this->getAvailabeQuantity();

    }



    /**
     * @return bool
     */
    public function check(){

        return true;

    }




}
