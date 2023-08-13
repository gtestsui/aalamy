<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRoster;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;

class EducatorRosterClass extends BaseManageRosterAbstract implements ManageRosterInterface,ManageRosterOwnerInterface
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    public function myRostersQuery(){
        $manageClass = new EducatorClassManagement($this->educator);
        $myClasses = $manageClass->myClasses();

        $myClassesIds = $myClasses->pluck('id')->toArray();
        $classInfosIds = ClassInfo::whereIn('class_id',$myClassesIds)->pluck('id')->toArray();
        $myRostersQuery = Roster::query();
        $myRostersQuery->whereIn('class_info_id',$classInfosIds);
        return  $myRostersQuery;

    }

    /**
     * @return Roster|Collection
     */
    public function myRostersByLevelSubjectId($levelSubjectId){
        $manageClass = new EducatorClassManagement($this->educator);
        $myClasses = $manageClass->myClasses();

        $myClassesIds = $myClasses->pluck('id')->toArray();
        $classInfosIds = ClassInfo::whereIn('class_id',$myClassesIds)
            ->where('level_subject_id',$levelSubjectId)
            ->pluck('id')
            ->toArray();
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
        $manageClass = new EducatorClassManagement($this->educator);
        $myClass = $manageClass->myClassesByIdOrFail($classId);

        $classInfo = ClassInfo::where('class_id',$myClass->id)->get();
        $classInfoIds = $classInfo->pluck('id')->toArray();

        $myRosters = Roster::whereIn('class_info_id',$classInfoIds)
//            ->with('ClassInfo')
            ->orderBy('class_info_id','asc')
            ->get();
        return $myRosters;
    }

}
