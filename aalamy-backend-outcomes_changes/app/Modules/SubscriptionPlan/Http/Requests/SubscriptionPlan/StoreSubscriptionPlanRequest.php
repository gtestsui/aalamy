<?php

namespace Modules\SubscriptionPlan\Http\Requests\SubscriptionPlan;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\SubscriptionPlan\Models\Module;
use Modules\SubscriptionPlan\Traits\ValidationAttributesTrans;

class StoreSubscriptionPlanRequest extends FormRequest
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


    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
          'name' => 'required',
          'description' => 'required',
          'is_paid' => 'required|boolean',
          'cost' => 'required|numeric',
          'billing_cycle' => ['required',Rule::in(config('SubscriptionPlan.panel.billing_cycles'))],
          'billing_cycle_days' => 'required_if:billing_cycle,'.config('SubscriptionPlan.panel.billing_cycles.fixed_count_of_days').'|numeric',
          'type' => ['required',Rule::in('school','educator')],
          'modules' => 'required|array',
//          'moduleIds.*' => 'nullable|   exists:modules,id',
          'modules.*.id' => 'required|exists:'.(new Module())->getTable().',id',
          'modules.*.number' => 'nullable|required_without:modules.*.can_use|integer|min:0',
          'modules.*.can_use' => 'nullable|required_without:modules.*.number|boolean',
        ];
    }




}
