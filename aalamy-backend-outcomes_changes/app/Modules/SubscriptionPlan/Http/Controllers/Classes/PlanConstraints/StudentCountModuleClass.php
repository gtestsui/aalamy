<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints;



use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\SubscriptionPlan\Models\Module;
use Modules\User\Http\Controllers\Classes\AccountDetails\AccountDetailsFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class StudentCountModuleClass extends BasePlanConstraintsClass
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
                'identify',getModuleIdentify('students_count')
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
    public static function createByOther(User $user,$accountObject)
    {
        $static = new static(true);

        $static->createdByOther = true;
        $static->user = $user;
        $static->accountObject = $accountObject;
        $static->accountType = $user->account_type;

        $static->module = Module::where(
                'identify',getModuleIdentify('students_count')
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
    public function canAddMoreStudents(){
        $this->checkAndSetUserSubscribe();
        $availableQuantityFromUserPlan = $this->getAvailabeQuantity();
        $myStudentsCount = $this->getCurrentStudentsCount();
        if($myStudentsCount >= $availableQuantityFromUserPlan)
            return false;

        return true;
    }

    /**
     * @return bool
     * @throws ErrorMsgException
     */
    public function canImportMoreStudents($importedStudentCount){
        $this->checkAndSetUserSubscribe();
        $availableQuantityFromUserPlan = $this->getAvailabeQuantity();
        $myStudentsCount = $this->getCurrentStudentsCount();
        if(($myStudentsCount+$importedStudentCount) >= $availableQuantityFromUserPlan)
            return false;

        return true;
    }



    /**
     * @throws ErrorMsgException
     * @return bool
     */
    public function check(){

        if($this->canAddMoreStudents())
            return true;

        throw new ErrorMsgException(transMsg(
            'you_have_reached_to_limit_of_your_subscription',
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME
        ));

    }

    /**
     * @throws ErrorMsgException
     * @return bool
     */
    public function checkForImport($importedStudentCount){

        if($this->canImportMoreStudents($importedStudentCount))
            return true;

        throw new ErrorMsgException(transMsg(
            'you_have_reached_to_limit_of_your_subscription',
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME
        ));

    }

    /**
     * @param string|null $msg
     * max_count_of_school_students
     * max_count_of_educator_students
     * @throws ErrorMsgException
     * @return bool
     * 
     */
    public function checkWithCustomizedErrorForStudent($msg=null){

        if($this->canAddMoreStudents())
            return true;

        $msg = $msg ?? transMsg('max_count_of_'.$this->accountType.'_students',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);
        throw new ErrorMsgException($msg);

    }


    /**
     * @return int
     */
    protected function getCurrentStudentsCount(){
        $accountDetails = AccountDetailsFactory::createByAccountTypeAndObject($this->accountType,$this->accountObject);
        return  $accountDetails->myStudentsCount();

    }




}
