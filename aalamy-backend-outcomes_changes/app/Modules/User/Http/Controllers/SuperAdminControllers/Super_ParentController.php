<?php

namespace Modules\User\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\User\Http\Controllers\Classes\AccountDetails\ParentDetailsClass;
use Modules\User\Http\Requests\SuperAdminRequests\ShowUserDetailsRequest;
use Modules\User\Models\ParentModel;

class Super_ParentController extends Controller
{

    public function getDetails(ShowUserDetailsRequest $request,$parent_id){
        $parent = ParentModel::findOrFail($parent_id);
        $parent->load('User');
        $detailsClass = new ParentDetailsClass($parent);
        $details = $detailsClass->getDetails();

        return ApiResponseClass::successResponse($details);

    }


}
