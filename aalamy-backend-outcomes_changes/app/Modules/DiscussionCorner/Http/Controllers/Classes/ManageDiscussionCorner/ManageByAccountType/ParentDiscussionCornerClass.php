<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Models\SchoolStudent;

class ParentDiscussionCornerClass implements ManageDiscussionCornerByTypeInterface
{

    protected ParentModel $parent;
    protected $paginateNum;
    private $myStudentIds,$myChildrenSchoolIds,$myChildrenEducatorIds;
    public function __construct(ParentModel $parent)
    {
        $this->parent = $parent;
        $this->paginateNum = config('DiscussionCorner.panel.post_count_per_page',10);

        //here get my student ids
        $studentParent = new StudentParentClass($this->parent);
        $this->myStudentIds = $studentParent->myStudentIds();

        //here get my studentSchool ids
        $schoolStudents = SchoolStudent::whereIn('student_id',$this->myStudentIds)
            ->active()->get();
        $this->myChildrenSchoolIds = $schoolStudents->pluck('school_id')->toArray();

        //here get  my studentEducators ids
        $this->myChildrenEducatorIds = EducatorStudent::whereIn('student_id',$this->myStudentIds)
            ->active()->pluck('educator_id')->toArray();

    }


    public function getPostsIHaveAccessToSeeQuery(){
        $postQuery =  DiscussionCornerPost::query()
            ->whereIn('school_id',$this->myChildrenSchoolIds)
                ->orWhereIn('educator_id',$this->myChildrenEducatorIds);
        return $postQuery;
    }

    public function getSurveysIHaveAccessToSeeQuery(){
        $surveyQuery =  DiscussionCornerSurvey::query()
            ->whereIn('school_id',$this->myChildrenSchoolIds)
            ->orWhereIn('educator_id',$this->myChildrenEducatorIds);
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
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->parent->user_id,false)
            ->paginate($this->paginateNum);
        return $surveys;
    }

}
