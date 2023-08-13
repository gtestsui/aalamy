<?php


namespace Modules\Notification\Http\Controllers\ManageManualNotification;



use App\Modules\Notification\Http\DTO\ManualNotificationData;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notification\Models\ManualNotification;
use Modules\Notification\Models\ManualNotificationReceiver;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;

class EducatorManualNotification extends BaseManualNotificationAbstract
{
    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    protected function getMySentManualNotificationQuery(){
        $query = ManualNotification::query()
            ->where('educator_id',$this->educator->id);
        return $query;
    }

    public function prepareNotificationReceivers(ManualNotificationData $manualNotificationData){

        $educatorClass = new StudentEducatorClass($this->educator);

        $this->parentUserIds = $this->prepareParentIds($manualNotificationData->parent_ids,$educatorClass,$manualNotificationData->all_parents);
        $this->studentUserIds = $this->prepareStudentIds($manualNotificationData->student_ids,$educatorClass,$manualNotificationData->all_students);

    }




}
