<?php


namespace Modules\Level\Http\Controllers\Classes\ManageSubject;


use App\Modules\Level\Http\Controllers\Classes\ManageSubject\BaseSubjectAbstract;
use Modules\Level\Models\Subject;
use Modules\User\Models\School;

class SchoolSubject extends BaseSubjectAbstract
{

    protected School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function mySubjectsQuery(){
        $mySubjectsQuery = Subject::query();
        $mySubjectsQuery->where('user_id',$this->school->user_id);
        return $mySubjectsQuery;
    }

//    /**
//     * when the subjectId is not null that mean
//     *  get mySubject by id or return null
//     */
//    public function mySubjects($subjectId=null): Collection
//    {
//        $mySubjectsQuery = $this->mySubjectsQuery();
//        $subjects = $mySubjectsQuery->get();
//        return  $subjects;
//    }
//
//    /**
//     * if @param int $levelId is null then will ignore the condition
//     * else will retrieve the subjects doesn't belong to this level before
//     */
//    public function mySubjectsExceptBelongsToLevel(?int $levelId): Collection
//    {
//        $mySubjectsQuery = $this->mySubjectsQuery();
//        $subjects = $mySubjectsQuery->whereDoesntHave('LevelSubjects',function ($query)use ($levelId){
//                $query->where('level_id',$levelId);
//            })
//            ->get();
//        return  $subjects;
//    }


}
