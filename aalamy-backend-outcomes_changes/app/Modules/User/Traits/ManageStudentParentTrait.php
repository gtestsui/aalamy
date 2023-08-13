<?php

namespace Modules\User\Traits;



use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\ParentModel;

trait ManageStudentParentTrait
{

    /**
     * @return ParentModel
     */
    public function myStudentParentsQuery(){
//        $myStudents = $this->myStudents();
//        $myStudentIds = $myStudents->pluck('student_id');
        $myStudentIds = $this->myStudentIds();

        $myStudentParentsQuery = ParentModel::query()
            ->search(Request('key'),[],[
                'User'
            ])
            ->hasStudent($myStudentIds)
            ->active();
        return $myStudentParentsQuery;
    }

    /**
     * @return ParentModel
     */
    public function myStudentParentsAll(){
        $myStudentParents = $this->myStudentParentsQuery()
            ->get();
        return $myStudentParents;
    }

    /**
     * @return ParentModel|Collection
     */
    public function myStudentParentsAllWithUserObject(){
        $myStudentParents = $this->myStudentParentsQuery()
            ->with('User')
            ->get();
        return $myStudentParents;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function myStudentParentsPaginate(){
        $myStudentParents = $this->myStudentParentsQuery()
            ->with('User')
            ->paginate(10);
        return $myStudentParents;
    }

    public function myStudentParentsByClassId($classId){
        $myStudentIds =  $this->myStudentsQuery()->whereHas('Student',function ($query)use ($classId){
            return $query->whereHas('ClassStudents',function ($query)use ($classId){
               return $query->active()->where('class_id',$classId);
            });
        })
            ->pluck('student_id')->toArray();

        $myStudentParents = ParentModel::query()
            ->with('User')
            ->hasStudent($myStudentIds)
            ->active()
            ->get();
        return $myStudentParents;

    }



}
