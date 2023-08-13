<?php

namespace Modules\Roster\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Controller;
use Modules\ClassModule\Models\ClassModel;
use Modules\Roster\Http\Controllers\Classes\ManageRosterInvitation\EducatorRosterInvitationClass;
use Modules\Roster\Http\Controllers\Classes\ManageRosterInvitation\SchoolRosterInvitationClass;
use Modules\Roster\Http\Requests\RosterInvitation\enrollToRosterByCodeRequest;
use Modules\Roster\Models\Roster;

class RosterInvitationController extends Controller
{

    public function enrollToRosterByCode(enrollToRosterByCodeRequest $request,$code){
        $user = $request->user();
        $user->load('Student');

        $roster = Roster::with('ClassInfo.ClassModel')
            ->where('code',$code)
            ->firstOrFail();

        if($roster->isClosed())
            throw new ErrorMsgException(transMsg('you_cant_enroll_to_closed_roster',ApplicationModules::ROSTER_MODULE_NAME));


        $classModel = ClassModel::with('Level.User')->find($roster->ClassInfo->ClassModel->id);
        if($classModel->Level->User->account_type == 'educator'){
            EducatorRosterInvitationClass::createByCode($classModel->Level->User->id)
                ->enroll($roster,$user->Student->id);
        }else{
            SchoolRosterInvitationClass::createByCode($classModel->Level->User->id)
                ->enroll($roster,$user->Student->id);
        }
        return ApiResponseClass::successResponse([
            'roster_id' => $roster->id
        ]);

    }




}
