<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints;



use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\SubscriptionPlan\Models\Module;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class AssignmentEditorModuleClass extends BasePlanConstraintsClass
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


    /**
     * owner here is school or educator
     * @param User $user
     * @return static
     */
    public static function createByOwner(User $user){
        $static = new static(true);

        $static->user = $user;

        list($static->accountType,$static->accountObject) = UserServices::getAccountTypeAndObject($user);

        $static->module = Module::where(
                'identify',getModuleIdentify('assignment_editor')
            )
            ->where('type',$static->accountType)
            ->firstOrFail();

        return $static;

    }


    /**
     * other here are teachers (when teacher trying to create quiz in his school if he has permission)
     * @param User $user(the owner of the plan schoolUser or educatorUser)
     * @param School|Educator $accountObject of the owner
     * @return static
     */
    public static function createByOther(User $user,$accountObject)
    {
        $static = new static(true);
        $static->createdByOther = true;
        $static->user = $user;
        $static->accountObject = $accountObject;
        $static->accountType = $user->account_type;

        $static->module = Module::where(
                'identify',getModuleIdentify('assignment_editor')
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
    public function canUse(){
        $this->checkAndSetUserSubscribe();
        $canUse = $this->getCanUseStatus();
        if($canUse)
            return true;

        return false;
    }




    /**
     * @throws ErrorMsgException
     * @return bool
     */
    public function check(){

        if($this->createdByOther)
            return $this->checkWithCustomizedErrorForTeacher();


        if($this->canUse())
            return true;

        throw new ErrorMsgException(transMsg(
            'this_feature_not_available_in_your_subscription',
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

        if($this->canUse())
            return true;

        $msg = $msg ?? transMsg('this_feature_not_available_in_your_subscription_for_other',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);
        throw new ErrorMsgException($msg);

    }




}
