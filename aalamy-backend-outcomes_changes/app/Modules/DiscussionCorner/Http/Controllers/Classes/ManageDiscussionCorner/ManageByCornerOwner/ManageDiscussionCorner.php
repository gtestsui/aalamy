<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner;


use Illuminate\Database\Eloquent\Collection;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

interface ManageDiscussionCorner
{

    public function checkAddPostByStudent(Student $student):void;

    public function checkAddPostByParent(ParentModel $parent):void;

    public function checkAddPostByTeacher(Teacher $teacher):void;

    public function checkAddPostBySchool(School $school):void;

    public function checkAddPostByEducator(Educator $educator):void;

    public function checkUpdatePost(User $user,DiscussionCornerPost $post):void;

    public function checkUpdateSurvey(User $user,DiscussionCornerSurvey $survey):void;

    public function checkDeletePost(User $user,DiscussionCornerPost $post):void;




    public function checkDisplayPostsByStudent(Student $student):void;

    public function checkDisplayPostsByParent(ParentModel $parent):void;

    public function checkDisplayPostsByTeacher(Teacher $teacher):void;

    public function checkDisplayPostsBySchool(School $school):void;

    public function checkDisplayPostsByEducator(Educator $educator):void;

    public function getPostsPaginate();

    public function getPostsWaitingApprovePaginate();


}
