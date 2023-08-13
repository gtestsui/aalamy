<?php

namespace Modules\User\Http\Controllers\Classes\AccountProfile;

use App\Modules\User\Http\Resources\ParentStudentResource;
use App\Modules\User\Http\Resources\SchoolStudentResource;
use Modules\StudentAchievement\Http\Resources\StudentAchievementResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class StudentProfile implements AccountProfileInterface
{

    private Student $student;
    private User $user;
    public function __construct(/*Student $student,*/User $user)
    {
        $this->user = $user;
//        $this->student = $student;
        $this->student = $this->user->Student;
    }

    public function profile(){
        $this->loadSchoolStudents();
        $this->loadParentStudents();
        $this->loadAchievements();
        return new UserResource($this->loadPersonal());

    }

//    public function profile(){
//        return[
//        'user' => new UserResource($this->personal()),
//        'schoolStudents' => SchoolStudentResource::collection($this->schoolStudents()),
//        'parentStudents' => ParentStudentResource::collection($this->parentStudents()),
//        'achievements' => StudentAchievementResource::collection($this->achievements()),
//        ];
//
//    }

    public function loadPersonal(){
        return $this->user;
    }

    public function loadSchoolStudents(){
        $this->student->load('AllSchoolStudent.School');
//        return $this->student->AllSchoolStudent;
    }

    public function loadParentStudents(){
        $this->student->load('ParentStudents.Parent.User');
//        return $this->student->ParentStudents;
    }

    public function loadAchievements(){
        $this->student->load('Achievements.User');
//        return $this->student->Achievements;

    }

}
