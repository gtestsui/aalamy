<?php

namespace Modules\User\Http\Controllers\Classes\AccountProfile;

use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\School;
use Modules\User\Models\User;

class SchoolProfile implements AccountProfileInterface
{

    private School $school;
    private User $user;
    public function __construct(/*Student $student,*/User $user)
    {
        $this->user = $user;
//        $this->student = $student;
        $this->school = $this->user->School;
    }

    public function profile(){
        $this->loadSchoolLevels();
        $this->loadTeacherAccounts();
//        $this->loadPersonal();
        return new UserResource($this->user);

    }

    public function loadPersonal(){
        return $this->user;
    }


    public function loadSchoolLevels(){
        $this->user->load('Levels.LevelSubjects.Subject');
//        return $this->student->ParentStudents;
    }

    public function loadTeacherAccounts(){
        return $this->school->load('Teachers.User');
//        return $this->student->AllSchoolStudent;
    }

}
