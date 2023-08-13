<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner;


use App\Exceptions\ErrorUnAuthorizationException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\DiscussionCorner\Traits\EducatorCornerAuthorization;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class EducatorDiscussionCorner implements ManageDiscussionCorner
{
    use  EducatorCornerAuthorization;

    //the owner of the corner
    protected $educator,$paginateNum;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
        $this->paginateNum = config('DiscussionCorner.panel.post_count_per_page',10);
    }

    public function getPostsPaginate(){
        $posts = DiscussionCornerPost::where('educator_id',$this->educator->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->approved()
            ->paginate($this->paginateNum);
        return $posts;
    }

    public function getPostsWaitingApprovePaginate($byPriority='desc'){
        $posts = DiscussionCornerPost::where('educator_id',$this->educator->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->orderBy('priority',$byPriority)
            ->approved(false)
            ->paginate($this->paginateNum);

        return $posts;
    }

    public function getSurveysPaginate(){
        $surveys = DiscussionCornerSurvey::where('educator_id',$this->educator->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->approved()
            ->paginate($this->paginateNum);
        return $surveys;
    }


    public function getSurveysWaitingApprovePaginate($byPriority='desc'){
        $surveys = DiscussionCornerSurvey::where('educator_id',$this->educator->id)
            ->withAllRelations()
            /*->deletedAsSoft(false)*/
            ->orderBy('priority',$byPriority)
            ->approved(false)
            ->paginate($this->paginateNum);

        return $surveys;
    }

}
