<?php


namespace Modules\Notification\Http\Controllers\ManageManualNotification;



use App\Http\Controllers\Classes\ServicesClass;
use App\Modules\Notification\Http\DTO\ManualNotificationData;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notification\Jobs\Manual\SendNewManualNotification;
use Modules\Notification\Models\ManualNotification;
use Modules\Notification\Models\ManualNotificationReceiver;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class SchoolManualNotification extends BaseManualNotificationAbstract
{
    private School $school;
    protected array $teacherUserIdsAsTeacherIdsAsKeys =[];

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * @return Builder
     */
    protected function getMySentManualNotificationQuery(){
        $query = ManualNotification::query()
            ->where('school_id',$this->school->id);
        return $query;
    }

    public function prepareNotificationReceivers(ManualNotificationData $manualNotificationData){

        $schoolClass = new StudentSchoolClass($this->school);

        $this->parentUserIds = $this->prepareParentIds($manualNotificationData->parent_ids,$schoolClass,$manualNotificationData->all_parents);
        $this->studentUserIds = $this->prepareStudentIds($manualNotificationData->student_ids,$schoolClass,$manualNotificationData->all_students);
        $this->teacherUserIdsAsTeacherIdsAsKeys = $this->prepareTeacherIds($manualNotificationData->teacher_ids,$manualNotificationData->all_teachers);

    }

    public function prepareTeacherIds(array $teacherIds,bool $all=false){

        $teachers = Teacher::where('school_id',$this->school->id)
            ->get();

        $teacherUserIdsAsTeacherIdsAsKeys = [];
        if($all) {
            $teacherUserIdsAsTeacherIdsAsKeys = $teachers
                ->pluck('user_id','id')
                ->toArray();
        }elseif(count($teachers)>0){
            //get the shared ids between my teacher and the teacher ids in request
            $teacherUserIdsAsTeacherIdsAsKeys = $teachers
                ->whereIn('id',$teacherIds)
                ->pluck('user_id','id')
                ->toArray();
        }

        return $teacherUserIdsAsTeacherIdsAsKeys;
    }


    /**
     * @override
     */
    public function insertReceiversToData(ManualNotification $manualNotification){

        ManualNotificationReceiver::insert(
            $this->prepareReceiversForCreate(
                $manualNotification,
                $this->parentUserIds ,
                $this->studentUserIds,
                array_values($this->teacherUserIdsAsTeacherIdsAsKeys)
            )
        );
    }


    /**
     * @override
     */
    public function dispatchNotification(User $fromUser,ManualNotification $manualNotification){
        ServicesClass::dispatchJob(new SendNewManualNotification(
            $this->parentUserIds ,
            $this->studentUserIds,
            $this->teacherUserIdsAsTeacherIdsAsKeys,
            $fromUser,
            $manualNotification
        ));
    }






}
