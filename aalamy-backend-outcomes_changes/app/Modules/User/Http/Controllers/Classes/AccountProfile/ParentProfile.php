<?php

namespace Modules\User\Http\Controllers\Classes\AccountProfile;

use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\ParentModel;
use Modules\User\Models\User;

class ParentProfile implements AccountProfileInterface
{

    private ParentModel $parent;
    private User $user;
    public function __construct(/*Student $student,*/User $user)
    {
        $this->user = $user;
//        $this->student = $student;
        $this->parent = $this->user->Parent;
    }

    public function profile(){
        $this->loadChilds();
//        $this->loadPersonal();
        return new UserResource($this->user);

    }

    public function loadPersonal(){
        return $this->user;
    }

    public function loadChilds(){
        return $this->parent->load('ParentStudents.Student.User');
//        return $this->student->AllSchoolStudent;
    }



}
