<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner;


use App\Exceptions\ErrorUnAuthorizationException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Traits\SchoolCornerAuthorization;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class SchoolDiscussionCorner implements ManageDiscussionCorner
{

    use SchoolCornerAuthorization;
    //the owner of the corner
    protected $school,$paginateNum;
    public function __construct(School $school)
    {
        $this->school = $school;
        $this->paginateNum = config('DiscussionCorner.panel.survey_count_per_page',10);

    }


    public function getPostsPaginate()
    {
        $posts = DiscussionCornerPost::where('school_id',$this->school->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->approved()
            ->paginate($this->paginateNum);
        return $posts;
    }

    public function getPostsWaitingApprovePaginate($byPriority='desc')
    {
        $posts = DiscussionCornerPost::where('school_id',$this->school->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->orderBy('priority',$byPriority)
            ->approved(false)
            ->paginate($this->paginateNum);
        return $posts;

    }

    public function getSurveysPaginate(){
        $surveys = DiscussionCornerSurvey::where('school_id',$this->school->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->approved()
            ->paginate($this->paginateNum);
        return $surveys;
    }

    public function getSurveysWaitingApprovePaginate($byPriority='desc'){
        $surveys = DiscussionCornerSurvey::where('school_id',$this->school->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->orderBy('priority',$byPriority)
            ->approved(false)
            ->paginate($this->paginateNum);

        return $surveys;
    }

}
