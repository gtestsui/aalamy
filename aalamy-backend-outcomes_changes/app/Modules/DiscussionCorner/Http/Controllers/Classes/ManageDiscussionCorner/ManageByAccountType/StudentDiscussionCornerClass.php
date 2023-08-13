<?php

namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\Student;

class StudentDiscussionCornerClass implements ManageDiscussionCornerByTypeInterface
{

    protected Student $student;
    protected $paginateNum;
    private $mySchoolId,$myEducatorIds;
    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->paginateNum = config('DiscussionCorner.panel.post_count_per_page',10);

        //here get my school id
        $studentClass = new StudentClass();
        $schoolStudent = $studentClass->getMyActiveSchoolStudent($this->student);
        /*$schoolStudent = SchoolStudent::where('student_id',$this->student->id)
            ->active()->first();*/
        $this->mySchoolId = !is_null($schoolStudent)?$schoolStudent->school_id:-1;

        //here get the educators ids
        $this->myEducatorIds = EducatorStudent::where('student_id',$this->student->id)
            ->active()->pluck('educator_id')->toArray();

    }

    public function getPostsIHaveAccessToSeeQuery(){
        $postQuery =  DiscussionCornerPost::query()
            ->where(function ($query){
                return $query->where('school_id',$this->mySchoolId)
                    ->orWhereIn('educator_id',$this->myEducatorIds);
            });
        return $postQuery;
    }

    public function getSurveysIHaveAccessToSeeQuery(){
        $surveyQuery =  DiscussionCornerSurvey::query()
            ->where(function ($query){
                return $query->where('school_id',$this->mySchoolId)
                    ->orWhereIn('educator_id',$this->myEducatorIds);
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


    public function getRandomSurveysPaginate(){

        $surveys = $this->getSurveysIHaveAccessToSeeQuery()
            ->imTheWriter($this->student->user_id,false)
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->student->user_id,false)
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
        $surveys = DiscussionCornerSurvey::where(function ($query){//this where responsible to get just items from current school and educators
            return $query->where('school_id',$this->mySchoolId)
                ->orWhereIn('educator_id',$this->myEducatorIds);
        })->/*where('school_id',$this->mySchoolId)
            ->orWhereIn('educator_id',$this->myEducatorIds)
            ->*/where('user_id',$this->student->user_id)
            ->withAllRelations()
            ->approved()
            ->answeredFromMe($this->student->user_id,false)
            ->paginate($this->paginateNum);
        return $surveys;
    }


}
