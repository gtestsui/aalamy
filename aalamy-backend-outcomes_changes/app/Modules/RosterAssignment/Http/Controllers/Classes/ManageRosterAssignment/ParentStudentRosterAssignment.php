<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment;


use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\StudentRosterClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class ParentStudentRosterAssignment extends BaseRosterAssignmentAbstract
{

    private Student $student;
    private ParentModel $parent;

    public function __construct(ParentModel $parent/*,Student $student*/)
    {
        $this->parent = $parent;
        $studentId = null;
        if(isset(request()->student_id)){
            $studentId = request()->student_id;
        }elseif(isset(request()->student_user_id)){
            $studentUser = User::with('Student')->findOrFail(request()->student_user_id);
            $studentId = $studentUser->Student->id;

        }
        $this->student = UserServices::getTargetedStudentByParent($studentId);

//        $this->student = Student::findOrFail(request()->student_id);

//        $this->student = $student;
    }

    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByMyAssignmentsQuery(){
        throw new ErrorMsgException('the parent cant use this function');
    }

    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByMyRostersQuery(){
        $rosterClass = new StudentRosterClass($this->student);

        if(isset($this->filterRosterAssignmentData) && count($this->filterRosterAssignmentData->roster_ids))
            $myRosters = $rosterClass->myRosterByIds($this->filterRosterAssignmentData->roster_ids);
        else
            $myRosters = $rosterClass->myRosters();

        $myRosterIds = $myRosters->pluck('id')->toArray();

        $myRosterAssignmentsQuery = RosterAssignment::query()
            ->whereIn('roster_id',$myRosterIds)
            ->filter($this->filterRosterAssignmentData)
//            ->isLocked(false)
            ->isHidden(false);

        return $myRosterAssignmentsQuery;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function myRosterAssignmentsByLevelSubjectPaginate($levelSubjectId){

        $myRosterAssignments = $this->myRosterAssignmentsByMyRostersQuery()
            ->whereHas('Assignment',function ($query)use ($levelSubjectId){
                return $query->where('level_subject_id',$levelSubjectId);
            })
            ->with(['Assignment','Roster','RosterAssignmentPages'=>function($query){
                return $query->iHaveAccessToDisplay($this->student->id)/*->isHidden(false)
                    ->where(function ($query){
                        return $query->whereHas('RosterAssignmentStudentPages',function ($query){
                            return $query->where('student_id',$this->student->id)
                                ->isHidden(false);
                        })
                        ->orWhereDoesntHave('RosterAssignmentStudentPages');
                    })*/
                    ->with('Page');
            }])
            ->paginate(10);

        return $myRosterAssignments;
    }

//    /**
//     * @return Collection
//     */
//    public function myRosterAssignmentsByAssignmentId($assignmentId):Collection
//    {
//        $assignmentClass = new StudentAssignment($this->student);
//        $assignment = $assignmentClass->myAssignmentByIdOrFail($assignmentId);
//        $rosterAssignments = RosterAssignment::where('assignment_id',$assignmentId)
//            ->with('Roster')
//            ->get();
//        return $rosterAssignments;
//    }

    /**
     * @return Builder
     */
    protected function myRosterAssignmentsByRosterIdQuery($rosterId)
    {

        $rosterClass = new StudentRosterClass($this->student);
        $roster = $rosterClass->myRosterByIdOrFail($rosterId);

        $rosterAssignments = RosterAssignment::query()
            ->where('roster_id',$rosterId)
            ->filter($this->filterRosterAssignmentData);

        return $rosterAssignments;
    }


    /**
     * @return LengthAwarePaginator
     */
    public function myRosterAssignmentsByMyRostersPaginate(){

        $myRosterAssignment = $this->myRosterAssignmentsByMyRostersQuery()
            ->with(['Assignment','Roster'])
            ->paginate(10);

        return $myRosterAssignment;
    }


    /**
     * @return RosterAssignment
     */
    public function loadDetails(RosterAssignment $rosterAssignment){
        $rosterAssignment->load(['Assignment'=>function($query)use($rosterAssignment){
            return $query->with(['LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            },'Unit','Lesson',
            'Pages'=>function($query)use($rosterAssignment){
                return $query->whereHas('RosterAssignmentPages',function ($query)use($rosterAssignment){
                   return $query->where('roster_assignment_id',$rosterAssignment->id)
                       ->isHidden(false)
                       ->whereHas('RosterAssignmentStudentPages',function ($query){
                           return $query->where('student_id',$this->student->id)
                               ->isHidden(false);
                       });
                });
            }]);
        }]);
        return $rosterAssignment;
    }

}
