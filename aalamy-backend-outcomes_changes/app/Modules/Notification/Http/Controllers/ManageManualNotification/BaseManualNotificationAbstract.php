<?php


namespace Modules\Notification\Http\Controllers\ManageManualNotification;


use App\Http\Controllers\Classes\ServicesClass;
use App\Modules\Notification\Http\DTO\ManualNotificationData;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Notification\Jobs\Manual\SendNewManualNotification;
use Modules\Notification\Models\ManualNotification;
use Modules\Notification\Models\ManualNotificationReceiver;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\User;

abstract class BaseManualNotificationAbstract
{

    protected array $parentUserIds =[];
    protected array $studentUserIds=[];
    /**
     * @return Builder
     */
    abstract protected function getMySentManualNotificationQuery();

    /**
     * @return array of User id
     */
    abstract public function prepareNotificationReceivers(ManualNotificationData $manualNotificationData);


    /**
     * @return LengthAwarePaginator
     */
    public function getMySentManualNotificationPaginate(){
        return $this->getMySentManualNotificationQuery()
            ->with('Receivers')
            ->paginate(10);
    }

    public function insertReceiversToData(ManualNotification $manualNotification){

        ManualNotificationReceiver::insert(
            $this->prepareReceiversForCreate(
                $manualNotification,
                $this->parentUserIds ,
                $this->studentUserIds,
            )
        );
    }


    /**
     * @param array $arrayOfArrayOfIds contains arrays and each array contain userIds
     */
    public function prepareReceiversForCreate(ManualNotification $manualNotification,array ...$arrayOfArrayOfIds){
        $arrayForCreate = [];
        foreach ($arrayOfArrayOfIds as $ids){
            if(count($ids)>0){
                foreach ($ids as $id){
                    $arrayForCreate[] = [
                        'manual_notification_id' => $manualNotification->id,
                        'user_id' => $id,
                        'created_at' => Carbon::now(),
                    ];
                }
            }
        }

        return $arrayForCreate;
    }



    /**
     * @override
     */
    public function dispatchNotification(User $fromUser,ManualNotification $manualNotification){
        ServicesClass::dispatchJob(new SendNewManualNotification(
            $this->parentUserIds ,
            $this->studentUserIds,
            [],
            $fromUser,
            $manualNotification
        ));
    }


    protected function prepareParentIds(array $parentIds,ManageStudentParentInterface $manageStudentParentClass,bool $all=false){

        $myStudentParents = $manageStudentParentClass->myStudentParentsAll();
        $myStudentParentIds = $myStudentParents->pluck('id')->toArray();

        if($all){
            $parentIds = $myStudentParentIds;
        }elseif(count($parentIds)>0){
            //get the shared ids between my student and the student in request
            $parentIds = array_intersect($myStudentParentIds,$parentIds);
        }

        $parentUserTarget = ParentModel::whereIn('id',$parentIds)
            ->pluck('user_id')->toArray();

        return $parentUserTarget;
    }



    protected function prepareStudentIds(array $studentIds,BaseManageStudentAbstract $manageStudentClass,bool $all=false){
        $myStudentIds = $manageStudentClass->myStudentIds();

        if($all){
            $studentIds = $myStudentIds;
        }elseif(count($studentIds)>0){
            //get the shared ids between my student and the student in request
            $studentIds = array_intersect($myStudentIds,$studentIds);
        }

        $studentUserTarget = Student::whereIn('id',$studentIds)
            ->pluck('user_id')->toArray();

        return $studentUserTarget;
    }



}
