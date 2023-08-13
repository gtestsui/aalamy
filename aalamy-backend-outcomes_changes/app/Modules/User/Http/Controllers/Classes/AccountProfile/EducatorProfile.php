<?php

namespace Modules\User\Http\Controllers\Classes\AccountProfile;

use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class EducatorProfile implements AccountProfileInterface
{

    private Educator $educator;
    private User $user;
    public function __construct(/*Student $student,*/User $user)
    {
        $this->user = $user;
//        $this->student = $student;
        $this->educator = $this->user->Educator;
    }

    public function profile(){
        $this->loadTeacherAccounts();
        $this->loadSubjects();
//        $this->loadPersonal();
        return new UserResource($this->user);

    }

    public function loadPersonal(){
        return $this->user;
    }

    public function loadTeacherAccounts(){
        return $this->user->load('Teachers.School');
//        return $this->student->AllSchoolStudent;
    }

    public function loadSubjects(){
        $this->user->load('Subjects.LevelSubjects.Level');
//        return $this->student->ParentStudents;
    }


}
