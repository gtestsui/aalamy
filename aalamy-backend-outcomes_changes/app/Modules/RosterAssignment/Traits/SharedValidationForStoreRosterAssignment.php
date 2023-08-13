<?php

namespace Modules\RosterAssignment\Traits;

use App\Http\Controllers\Classes\ApplicationModules;
use Modules\User\Models\Teacher;

trait SharedValidationForStoreRosterAssignment
{
    private $moduleName = ApplicationModules::ROSTER_ASSIGNMENT_MODULE_NAME;


    public function getSharedValidationRuleArrayForStore(){
        return [

            'is_locked' => 'boolean',
            'is_hidden' => 'boolean',
            'prevent_request_help' => 'boolean',
            'display_mark' => 'boolean',
            'is_auto_saved' => 'boolean',
            'prevent_moved_between_pages' => 'boolean',
            'is_shuffling' => 'boolean',


            'start_date' => ['required','after_or_equal:'.date('Y-m-d'),'date_format:'.config('panel.standard_date_time_format')],
            'expiration_date' => ['required','date_format:'.config('panel.standard_date_time_format'),'after:start_date'],

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

//    public function attributes(){
//        return [
//            'name' => transValidationParameter('name',$this->moduleName),
//            'date' => transValidationParameter('date',$this->moduleName),
//        ];
//    }
}
