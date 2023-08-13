<?php

namespace Modules\Level\Http\Requests\LevelSubject;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Models\Subject;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Level;

class RelateToMoreThanSubjectRequest extends FormRequest
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

    protected $subject,$level;
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

        $level = Level::findOrFail($this->level_id);
        LevelServices::checkOwnerLevelAuthorization($user,$level);
        $this->setLevel($level);
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
            'subject_ids' => 'required|array',
//            'subject_ids.*' => 'required|exists:subjects,id',
            'subject_ids.*' => 'required|exists:'.(new Subject())->getTable().',id',
//            'level_id' => 'required|exists:levels,id',
            'level_id' => 'required|exists:'.(new Level())->getTable().',id',
        ];
    }



    public function setLevel($level){
        $this->level = $level;
    }



    public function getLevel(){
        return $this->level;
    }

}
