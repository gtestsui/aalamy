<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;


use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Teacher;

class TeacherRosterClass extends BaseManageRosterAbstract  implements ManageRosterInterface,ManageRosterOwnerInterface
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Builder
     */
    public function myRostersQuery(){

        $classInfosIds = ClassInfo::where('teacher_id',$this->teacher->id)
            ->pluck('id')->toArray();

        $myRostersQuery = Roster::query();
        $myRostersQuery->whereIn('class_info_id',$classInfosIds);
        return  $myRostersQuery;

    }

    /**
     * @return Roster|Collection
     */
    public function myRostersByLevelSubjectId($levelSubjectId){

        $classInfosIds = ClassInfo::where('teacher_id',$this->teacher->id)
            ->where('level_subject_id',$levelSubjectId)
            ->pluck('id')->toArray();

        $myRostersQuery = Roster::query();
        $myRostersQuery->whereIn('class_info_id',$classInfosIds);
        return  $myRostersQuery->get();

    }

    /**
     *
     */
    public function myRosterByIdWithRosterAssignment($id){
        $myRosterWithRosterAssignment = $this->myRosterByIdQuery($id)
//            ->with('RosterAssignments.Assignment')
            ->with(['RosterAssignments'=>function($query){
                return $query->withStudentActionsStatistics()
                    ->with('Assignment');
            }])
            ->with('ClassInfo')
            ->firstOrFail();
        return $myRosterWithRosterAssignment;

    }


    /**
     * @return Collection of Roster
     */
    public function myRostersDoesntLinkedToAssignment($assignmentId){
        $myRostersDoesntLinkedToAssignmet = $this->myRostersQuery()
            ->whereDoesntHave('RosterAssignments',function ($query)use ($assignmentId){
                return $query->where('assignment_id',$assignmentId);
            })
            ->get();
        return $myRostersDoesntLinkedToAssignmet;
    }



    /**
     * @param mixed $classId
     * @return Collection of Roster
     */
    public function allMyRostersByClassId($classId){
        $classInfo = ClassInfo::where('teacher_id',$this->teacher->id)
            ->where('class_id',$classId)
            ->get();
        $classInfoIds = $classInfo->pluck('id')->toArray();

//        if(is_null($classInfo))
//            throw new ErrorMsgException('this class doesnt belongs to you');

        $myRosters = Roster::whereIn('class_info_id',$classInfoIds)
//            ->with('ClassInfo')
            ->get();
        return $myRosters;
    }

}
