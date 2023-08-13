<?php

namespace Modules\LearningResource\Http\Requests\LearningResource;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\Page;
use Modules\DiscussionCorner\Traits\ValidationAttributesTrans;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Models\Topic;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreLearningResourceRequest extends FormRequest
{

    /**
     * @uses ResponseValidationFormRequest it is responsible to return validation
     * messages error as json
     * @uses AuthorizesAfterValidation it is responsible to call authorizeValidated
     * after check on validation rules
     * @uses ValidationAttributesTrans it is responsible to translate the parameters
     * in rule array
     */
    use ResponseValidationFormRequest,AuthorizesAfterValidation,ValidationAttributesTrans;

//    private Assignment $assignment;
    private Topic $topic;

    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {

        $user = $this->user();
        UserServices::checkRoles($user,['educator','school']);

        $topic = LearningResourceServices::checkUseTopic($user,$this->topic_id);
        /*if(isset($this->assignment_id)){
            $assignment = Assignment::findOrFail($this->assignment_id);
            AssignmentServices::checkUseAssignmentAuthorization($assignment,$user,$this->my_treacher_id);
            $this->setAssignment($assignment);
        }*/
        LevelServices::checkUnitAndLessonBelongsToLevelSubject($this->level_subject_id,$this->unit_id,$this->lesson_id);
        LevelServices::checkUseLevelSubjectAuthorization($this->level_subject_id,$user,$this->my_teacher_id);

//        LearningResourceServices::checkValidShareTypeWithMyAccount($user,$this->my_teacher_id,$this->share_type);

        $this->setTopic($topic);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'share_type' => ['required',Rule::in(configFromModule('panel.learning_resource_read_share_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME))],
            'topic_id' => 'required|exists:'.(new Topic())->getTable().',id',
            'level_subject_id' => 'required|exists:'.(new LevelSubject())->getTable().',id',
            'unit_id' => 'required_with:lesson_id|exists:'.(new Unit())->getTable().',id',
            'lesson_id' => 'nullable|exists:'.(new Lesson())->getTable().',id',

            'name' => 'required',
            'file' => 'required_without:assignment_id|file',
            'file_type' => ['required_with:file',Rule::in(configFromModule('panel.learning_resource_file_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME))],
            'assignment_id' => 'required_without:file|exists:'.(new Assignment())->getTable().',id',
            'page_ids' => 'nullable|array',
            'page_ids.*' => 'nullable|exists:'.(new Page())->getTable().',id',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    /*public function setAssignment(Assignment $assignment){
        $this->assignment = $assignment;
    }

    public function getAssignment(){
        return $this->assignment;
    }*/




    public function setTopic(Topic $topic){
        $this->topic = $topic;
    }

    public function getTopic(){
        return $this->topic;
    }

}
