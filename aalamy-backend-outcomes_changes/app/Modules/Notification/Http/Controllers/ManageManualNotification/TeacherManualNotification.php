<?php


namespace Modules\Notification\Http\Controllers\ManageManualNotification;



use App\Modules\Notification\Http\DTO\ManualNotificationData;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notification\Models\ManualNotification;
use Modules\Notification\Models\ManualNotificationReceiver;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\Teacher;

class TeacherManualNotification extends BaseManualNotificationAbstract
{
    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }


    /**
     * @return Builder
     */
    protected function getMySentManualNotificationQuery(){
        $query = ManualNotification::query()
            ->where('teacher_id',$this->teacher->id);
        return $query;
    }

    public function prepareNotificationReceivers(ManualNotificationData $manualNotificationData){

        $teacherClass = new StudentTeacherClass($this->teacher);

        $this->parentUserIds = $this->prepareParentIds($manualNotificationData->parent_ids,$teacherClass,$manualNotificationData->all_parents);
        $this->studentUserIds = $this->prepareStudentIds($manualNotificationData->student_ids,$teacherClass,$manualNotificationData->all_students);

    }





}
