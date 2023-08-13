<?php


namespace App\Modules\Level\Http\Controllers\Classes\ManageSubject;


use Illuminate\Database\Eloquent\Collection;
use Modules\Level\Models\Subject;

interface ManageSubjectInterface
{



    /**
     * @return Subject|Collection
     */
    public function mySubjects(): Collection;


    /**
     * @param mixed|int $semester
     * @param mixed|int @$level_id
     * @return Subject|Collection
     */
    public function mySubjectsBySemester($semester,$level_id=null);


    /**
     * @param $id
     * @return Subject|null
     */
    public function mySubjectById($id);

    /**
     * if @param int $levelId is null then will ignore the condition
     * else will retrieve the subjects doesn't belong to this level before
     */
    public function mySubjectsExceptBelongsToLevel(?int $levelId): Collection;

}
