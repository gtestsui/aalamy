<?php

namespace Modules\DiscussionCorner\Traits;
use App\Exceptions\ErrorUnAuthorizationException;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

trait EducatorCornerAuthorization
{

    public function checkAddPostByStudent(Student $student):void
    {
        $studentEducator = new StudentEducatorClass($this->educator);
        $myStudentById = $studentEducator->myStudentByStudentId($student->id);
        if(is_null($myStudentById))
            throw new ErrorUnAuthorizationException();
    }

    public function checkAddPostByParent(ParentModel $parent):void
    {

        $studentEducator = new StudentEducatorClass($this->educator);
        $parents = $studentEducator->myStudentParentsAll();
        $parentIds = $parents->pluck('id')->toArray();
        if(!in_array($parent->id,$parentIds))
            throw new ErrorUnAuthorizationException();

        /*$studentParent = new StudentParentClass($parent);
        $myStudents = $studentParent->myStudents();
        $studentIds = $myStudents->pluck('student_id');
        $educatorId = $this->educator->id;
        $parenStudent = Student::whereIn('id',$studentIds)
            ->whereHas('SchoolEducator',function ($query)use($educatorId){
                $query->where('educator_id',$educatorId);
            })
            ->first();
        if(is_null($parenStudent))
            throw new ErrorUnAuthorizationException();*/
    }

    public function checkAddPostByTeacher(Teacher $teacher):void
    {

        if($teacher->user_id != $this->educator->user_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkAddPostBySchool(School $school):void
    {

        throw new ErrorUnAuthorizationException();
    }

    public function checkAddPostByEducator(Educator $educator):void
    {

        if($this->educator->id != $educator->id)
            throw new ErrorUnAuthorizationException();
    }



//    public function checkUpdatePostByStudent(Student $student):void
//    {
//        $this->checkAddPostByStudent($student);
//    }
//
//    public function checkUpdatePostByParent(ParentModel $parent):void
//    {
//        $this->checkAddPostByParent($parent);
//    }
//
//    public function checkUpdatePostByTeacher(Teacher $teacher):void
//    {
//
//        throw new ErrorUnAuthorizationException();
//    }
//
//    public function checkUpdatePostBySchool(School $school):void
//    {
//
//        throw new ErrorUnAuthorizationException();
//    }
//
//    public function checkUpdatePostByEducator(Educator $educator):void
//    {
//        $this->checkAddPostByEducator($educator);
//
//    }


    public function checkUpdatePost(User $user,DiscussionCornerPost $post):void
    {
        //check if im the owner of the post
        if($user->id != $post->user_id )
            throw new ErrorUnAuthorizationException();

    }

    public function checkUpdateSurvey(User $user,DiscussionCornerSurvey $survey):void
    {
        //check if im the owner of the post
        if($user->id != $survey->user_id )
            throw new ErrorUnAuthorizationException();

    }


    public function checkDeletePost(User $user,DiscussionCornerPost $post):void
    {
        // check if im the owner the corner or im owner the post
        if($post->user_id != $user->id && $user->id != $this->educator->user_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkDeleteSurvey(User $user,DiscussionCornerSurvey $survey):void
    {
        // check if im the owner the corner or im owner the post
        if($survey->user_id != $user->id && $user->id != $this->educator->user_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkApprovePost(User $user,DiscussionCornerPost $post):void
    {
        // check if im the owner the corner
        if($user->id != $this->educator->user_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkApproveSurvey(User $user,DiscussionCornerSurvey $survey):void
    {
        // check if im the owner the corner
        if($user->id != $this->educator->user_id)
            throw new ErrorUnAuthorizationException();
    }




    public function checkDisplayPostsByStudent(Student $student):void
    {
        $this->checkAddPostByStudent($student);
    }

    public function checkDisplayPostsByParent(ParentModel $parent):void
    {
        $this->checkAddPostByParent($parent);

    }

    public function checkDisplayPostsByTeacher(Teacher $teacher):void
    {
        $this->checkAddPostByTeacher($teacher);

    }

    public function checkDisplayPostsBySchool(School $school):void
    {
        $this->checkAddPostBySchool($school);

    }

    public function checkDisplayPostsByEducator(Educator $educator):void
    {
        $this->checkAddPostByEducator($educator);

    }


    public function checkReplyOnPostByStudent(Student $student):void
    {
        $this->checkAddPostByStudent($student);
    }

    public function checkReplyOnPostByParent(ParentModel $parent):void
    {
        $this->checkAddPostByParent($parent);

    }

    public function checkReplyOnPostByTeacher(Teacher $teacher):void
    {
        $this->checkAddPostByTeacher($teacher);

    }

    public function checkReplyOnPostBySchool(School $school):void
    {
        $this->checkAddPostBySchool($school);

    }

    public function checkReplyOnPostByEducator(Educator $educator):void
    {
        $this->checkAddPostByEducator($educator);

    }


}
