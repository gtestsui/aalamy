<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;

abstract class BaseRosterAssignmentAbstract
{


    protected ?FilterRosterAssignmentData $filterRosterAssignmentData=null;

    /**
     * @return Builder
     */
    abstract protected function myRosterAssignmentsByMyAssignmentsQuery();

    /**
     * @return Builder
     */
    abstract protected function myRosterAssignmentsByMyRostersQuery();

    /**
     * @return Builder
     */
    abstract protected function myRosterAssignmentsByRosterIdQuery($rosterId);


    public function setFilter(FilterRosterAssignmentData $filterRosterAssignmentData){
        $this->filterRosterAssignmentData = $filterRosterAssignmentData;
        return $this;
    }

//    /**
//     * @return RosterAssignment
//     */
    /**
     * @return Builder[]|Collection
     */
    public function myRosterAssignmentsByMyRosters(){

        $myRosterAssignment = $this->myRosterAssignmentsByMyRostersQuery()
            ->get();

        return $myRosterAssignment;
    }


    /**
     * @return LengthAwarePaginator
     */
    public function myRosterAssignmentsByMyRostersPaginate(){

        $myRosterAssignment = $this->myRosterAssignmentsByMyRostersQuery()
            ->with(['Assignment','Roster.ClassInfo.ClassModel'])
            ->withStudentActionsStatistics()
            ->paginate(10);

        return $myRosterAssignment;
    }


    /**
     * filter by every roster belongs to me
     * @return array
     */
    public function myRosterAssignmentsIdsByMyRosters()
    {
        $rosterAssignmentsIds = $this->myRosterAssignmentsByMyRostersQuery()
//            ->when(!is_null($this->filterRosterAssignmentData),function ($query){
//                return $query->when(!is_null($this->filterRosterAssignmentData->start_date) ,function ($query){
//                    return $query->whereDate('start_date','>=',$this->filterRosterAssignmentData->start_date);
//                })
//                ->when(!is_null($this->filterRosterAssignmentData->end_date),function ($query){
//                    return $query->whereDate('expiration_date','<=',$this->filterRosterAssignmentData->end_date);
//                })
//                ->when(count($this->filterRosterAssignmentData->roster_assignment_ids),function ($query){
//                    return $query->whereIn('id',$this->filterRosterAssignmentData->roster_assignment_ids);
//                });
//            })
            ->pluck('id')
            ->toArray();
        return $rosterAssignmentsIds;
    }


    /**
     * @param $rosterAssignmentId
     * @return Builder|RosterAssignment|null
     */
    public function myRosterAssignmentsByMyRostersByRosterAssignmentId($rosterAssignmentId){

        $myRosterAssignment = $this->myRosterAssignmentsByMyRostersQuery()
            ->where('id',$rosterAssignmentId)
            ->first();

        return $myRosterAssignment;
    }

    /**
     * @return Builder|RosterAssignment|null
     */
    public function myRosterAssignmentsByMyRostersByRosterAssignmentIdOrFail($rosterAssignmentId){

        $myRosterAssignment = $this->myRosterAssignmentsByMyRostersQuery()
            ->where('id',$rosterAssignmentId)
            ->firstOrFail();

        return $myRosterAssignment;
    }


    /**
     * @return RosterAssignment of RosterAssignment where belongs to myAssignments
     * and by month or day from date
     */
    public function myRosterAssignmentsByMyAssignmentsAndPartOfDateFromStartDate(Carbon $date,$partOfDateName){
        $rosterAssignments = $this->myRosterAssignmentsByMyAssignmentsQuery()
//            ->whereMonth('start_date',$date->month)
            ->{'by'.ucfirst($partOfDateName).'FromStartDate'}($date)
            ->orderBy('start_date','asc')
            ->withAllRelations()
            ->get();
        return $rosterAssignments;
    }

    /**
     * @return RosterAssignment of RosterAssignment where belongs to myRosters
     * and by month or day from date
     */
    public function myRosterAssignmentsByMyRostersAndPartOfDateFromStartDate(Carbon $date,$partOfDateName){
        $rosterAssignments = $this->myRosterAssignmentsByMyRostersQuery()
//            ->whereMonth('start_date',$date->month)
            ->{'by'.ucfirst($partOfDateName).'FromStartDate'}($date)
            ->orderBy('start_date','asc')
            ->withAllRelations()
            ->get();
        return $rosterAssignments;
    }


    /**
     * @return Collection
     */
    public function myRosterAssignmentsByRosterId($rosterId)
    {

        $rosterAssignments = $this->myRosterAssignmentsByRosterIdQuery($rosterId)
            ->with('Assignment')
            ->get();

        return $rosterAssignments;
    }

    public function myEndedRosterAssignmentsByRosterId($rosterId)
    {

        $rosterAssignments = $this->myRosterAssignmentsByRosterIdQuery($rosterId)
//            ->whereDate('expiration_date','<=',Carbon::now())
            ->where('expiration_date','<=',Carbon::now())
            ->with('Assignment')
            ->get();

        return $rosterAssignments;
    }


    /**
     * filter by defined roster
     * @return array
     */
    public function myRosterAssignmentsIdsByRosterId($rosterId)
    {
        $rosterAssignments = $this->myRosterAssignmentsByRosterIdQuery($rosterId)
//            ->when(!is_null($this->filterRosterAssignmentData),function ($query){
//                return $query->when(!is_null($this->filterRosterAssignmentData->start_date),function ($query){
//                    return $query->whereDate('start_date','>=',$this->filterRosterAssignmentData->start_date);
//                })
//                ->when(!is_null($this->filterRosterAssignmentData->end_date),function ($query){
//                    return $query->whereDate('expiration_date','<=',$this->filterRosterAssignmentData->end_date);
//                });
//            })
            ->pluck('id')
            ->toArray();
        return $rosterAssignments;
    }


    /**
     * @return RosterAssignment
     */
    public function loadDetails(RosterAssignment $rosterAssignment){
        $rosterAssignment->load(['Assignment'=>function($query){
            return $query->with(['LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            },'Unit','Lesson','Pages']);
        }]);
        return $rosterAssignment;
    }


}
