<?php

namespace Modules\DiscussionCorner\Traits;
use App\Exceptions\ErrorUnAuthorizationException;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

trait SchoolCornerAuthorization{

    public function checkAddPostByStudent(Student $student):void
    {
        $studentSchool = new StudentSchoolClass($this->school);
        $myStudentById = $studentSchool->myStudentByStudentId($student->id);
        if(is_null($myStudentById))
            throw new ErrorUnAuthorizationException();
    }

    public function checkAddPostByParent(ParentModel $parent):void
    {
        $studentParent = new StudentParentClass($parent);
//        $myStudents = $studentParent->myStudents();
//        $studentIds = $myStudents->pluck('student_id');
        $studentIds = $studentParent->myStudentIds();
        $schoolId = $this->school->id;
        $parenStudent = Student::whereIn('id',$studentIds)
            ->whereHas('SchoolStudent',function ($query)use($schoolId){
                $query->where('school_id',$schoolId);
            })
            ->first();
        if(is_null($parenStudent))
            throw new ErrorUnAuthorizationException();
    }

    public function checkAddPostByTeacher(Teacher $teacher):void
    {

        if($teacher->school_id != $this->school->id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkAddPostBySchool(School $school):void
    {

        if($school->id != $this->school->id)
            throw new ErrorUnAuthorizationException();
    }

    /**
     * @note if the educator trying to add inside school
     * we should check if has a teacher account inside this school
     */
    public function checkAddPostByEducator(Educator $educator):void
    {

        $teacher = Teacher::definedEducatorBelongToSchool($educator->user_id,$this->school->id)
            ->first();

        if(is_null($teacher))
            throw new ErrorUnAuthorizationException();

    }


//    public function checkUpdatePostByStudent(Student $student,DiscussionCornerPost $post):void
//    {
//        $this->checkAddPostByStudent($student);
//        if($student->user_id != $post->user_id )
//            throw new ErrorUnAuthorizationException();
//
//    }
//
//    public function checkUpdatePostByParent(ParentModel $parent,DiscussionCornerPost $post):void
//    {
//        $this->checkAddPostByParent($parent);
//        if($parent->user_id != $post->user_id )
//            throw new ErrorUnAuthorizationException();
//    }
//
//    public function checkUpdatePostByTeacher(Teacher $teacher,DiscussionCornerPost $post):void
//    {
//
//        $this->checkAddPostByTeacher($teacher);
//        if($teacher->user_id != $post->user_id )
//            throw new ErrorUnAuthorizationException();
//    }
//
//    public function checkUpdatePostBySchool(School $school,DiscussionCornerPost $post):void
//    {
//        $this->checkAddPostBySchool($school);
//        if($school->user_id != $post->user_id )
//            throw new ErrorUnAuthorizationException();
//
//    }
//
//    public function checkUpdatePostByEducator(Educator $educator,DiscussionCornerPost $post):void
//    {
//
//        throw new ErrorUnAuthorizationException();
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
        //check if im the owner the corner or im owner the post
        if($post->user_id != $user->id && $user->id != $this->school->user_id)
            throw new ErrorUnAuthorizationException();
    }


    public function checkDeleteSurvey(User $user,DiscussionCornerSurvey $survey):void
    {
        // check if im the owner the corner or im owner the post
        if($survey->user_id != $user->id && $user->id != $this->school->user_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkApprovePost(User $user,DiscussionCornerPost $post):void
    {
        // check if im the owner the corner
        if($user->id != $this->school->user_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkApproveSurvey(User $user,DiscussionCornerSurvey $survey):void
    {
        // check if im the owner the corner
        if($user->id != $this->school->user_id)
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
