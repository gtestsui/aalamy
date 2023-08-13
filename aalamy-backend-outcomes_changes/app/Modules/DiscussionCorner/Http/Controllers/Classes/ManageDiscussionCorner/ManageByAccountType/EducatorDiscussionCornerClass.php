<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorDiscussionCornerClass implements ManageDiscussionCornerByTypeInterface
{

    protected Educator $educator;
    protected $paginateNum;
    private $mySchoolIds = [];
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
        $this->paginateNum = config('DiscussionCorner.panel.post_count_per_page',10);

        //get the student ids I am teacher on it
        $myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
            ->get();
        if(count($myTeacherAccounts)){
            $this->mySchoolIds = $myTeacherAccounts->pluck('school_id')->toArray();
        }

    }

    public function getPostsIHaveAccessToSeeQuery(){
        $postQuery =  DiscussionCornerPost::query();
        $postQuery->where(function ($query){
            return $query->whereIn('school_id',$this->mySchoolIds)
                ->orWhere('educator_id',$this->educator->id);
        });
        return $postQuery;
    }

    public function getSurveysIHaveAccessToSeeQuery(){
        $surveyQuery =  DiscussionCornerSurvey::query();
        $surveyQuery->where(function ($query){
            return $query->whereIn('school_id',$this->mySchoolIds)
                ->orWhere('educator_id',$this->educator->id);
        });
        return $surveyQuery;
    }

    public function getRandomPostsPaginate(){

        $posts = $this->getPostsIHaveAccessToSeeQuery()
            ->withCount('Replies')
            ->withAllRelations()
            ->approved()
            ->paginate($this->paginateNum);
        return $posts;
    }

    /**
     * @return DiscussionCornerPost
     */
    public function getPostIHaveAccessToSeeById($id){
        $post = $this->getPostsIHaveAccessToSeeQuery()
            ->where('id',$id)
            ->first();
        return $post;
    }

    /**
     * @throws ModelNotFoundException
     * @return DiscussionCornerPost
     */
    public function getPostIHaveAccessToSeeByIdOrFail($id){
        $post = $this->getPostsIHaveAccessToSeeQuery()
            ->where('id',$id)
            ->firstOrFail();
        return $post;
    }

    public function getRandomSurveysPaginate(){

        $surveys = $this->getSurveysIHaveAccessToSeeQuery()
            ->imTheWriter($this->educator->user_id,false)
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->educator->user_id,false)
            ->paginate($this->paginateNum);
        return $surveys;
    }

    public function getMySurveysPaginate(){

        $surveys = $this->getSurveysIHaveAccessToSeeQuery()
            ->imTheWriter($this->educator->user_id,true)
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->educator->user_id,false)
            ->paginate($this->paginateNum);
        return $surveys;
    }



}
