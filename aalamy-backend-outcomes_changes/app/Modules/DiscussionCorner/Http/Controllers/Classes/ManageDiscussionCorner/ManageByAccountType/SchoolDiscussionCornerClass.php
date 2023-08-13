<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Models\School;

class SchoolDiscussionCornerClass implements ManageDiscussionCornerByTypeInterface
{

    protected School $school;
    protected $paginateNum;
    public function __construct(School $school)
    {
        $this->school = $school;
        $this->paginateNum = config('DiscussionCorner.panel.post_count_per_page',10);


    }

    public function getPostsIHaveAccessToSeeQuery(){
        $postQuery =  DiscussionCornerPost::query()
            ->where('school_id',$this->school->id);
        return $postQuery;
    }

    public function getSurveysIHaveAccessToSeeQuery(){
        $surveyQuery =  DiscussionCornerSurvey::query()
            ->where('school_id',$this->school->id);
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

    public function getRandomSurveysPaginate(){

        $surveys = $this->getSurveysIHaveAccessToSeeQuery()
            ->imTheWriter($this->school->user_id,false)
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->school->user_id,false)
            ->paginate($this->paginateNum);
        return $surveys;

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

    public function getMySurveysPaginate(){

        $surveys = $this->getSurveysIHaveAccessToSeeQuery()
            ->imTheWriter($this->school->user_id,true)
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->school->user_id,false)
            ->paginate($this->paginateNum);
        return $surveys;

    }

}
