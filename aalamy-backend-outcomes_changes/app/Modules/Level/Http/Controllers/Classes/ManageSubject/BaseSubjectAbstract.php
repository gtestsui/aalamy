<?php


namespace App\Modules\Level\Http\Controllers\Classes\ManageSubject;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Level\Models\Subject;

abstract class BaseSubjectAbstract implements ManageSubjectInterface
{


    /**
     * @return Builder
     */
    abstract public function mySubjectsQuery();


    /**
     * @return Subject|Collection|Builder
     */
    public function mySubjects(): Collection
    {
        $mySubjectsQuery = $this->mySubjectsQuery();
        $subjects = $mySubjectsQuery->get();
        return  $subjects;
    }


    /**
     * @inheritDoc
     */
    public function mySubjectsBySemester($semester,$level_id=null){
        $mySubjectsQuery = $this->mySubjectsQuery();
        $subjects = $mySubjectsQuery->where('semester',$semester)
            ->whereHas('LevelSubjects',function ($query)use ($level_id){
                return $query->where('level_id',$level_id);
            })
            ->get();
        return  $subjects;
    }

    /**
     * @param $id
     * @return Subject|null|Builder
     */
    public function mySubjectById($id){
        $mySubject = $this->mySubjectsQuery()
            ->where('id',$id)
            ->first();
        return $mySubject;
    }

    /**
     * if @param int $levelId is null then will ignore the condition
     * else will retrieve the subjects doesn't belong to this level before
     */
    public function mySubjectsExceptBelongsToLevel(?int $levelId): Collection
    {
        $mySubjectsQuery = $this->mySubjectsQuery();
        $subjects = $mySubjectsQuery->whereDoesntHave('LevelSubjects',function ($query)use ($levelId){
            $query->where('level_id',$levelId);
        })
            ->get();
        return  $subjects;
    }

}
