<?php

namespace Modules\RosterAssignment\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentPage\HideOrUnHideRosterAssignmentPageRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentPage\LockOrUnLockRosterAssignmentPageRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentPage\ShowRosterAssignmentPageForParentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentPage\ShowRosterAssignmentPageRequest;
use Modules\Assignment\Http\Resources\PageResource;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentPageResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\User\Http\Controllers\Classes\UserServices;

class RosterAssignmentPageController extends Controller
{





    /**
     * the result is object have key as page_id and the value its object too
     * and have key as student_id and value its object contains is_hidden and is_locked
     */
    public function getByRosterAssignmentIdWithStudentPages($roster_assignment_id){
        $map = [];
        $rosterAssignmentPages = RosterAssignmentPage::where('roster_assignment_id',$roster_assignment_id)
            ->with('RosterAssignmentStudentPages')
            ->get()
            ->each(function ($rosterAssignmentPage)use (&$map){
                $rosterAssignmentPage->RosterAssignmentStudentPages->each(function ($studentPage)use(&$map,$rosterAssignmentPage){
                    $map[$rosterAssignmentPage->page_id][$studentPage->student_id] =  [
                        'is_hidden' => $studentPage->is_hidden,
                        'is_locked' => $studentPage->is_locked,
                    ];
                });
            });
//            ->groupBy(['page_id','RosterAssignmentStudentPages.student_id']);
//return $map;
        return ApiResponseClass::successResponse($map);

    }



    public function show(ShowRosterAssignmentPageRequest $request,$roster_assignment_id,$page_id){
        $user = $request->user();
        $rosterAssignmentPageClass = $request->getRosterAssignmentPageClass();
        $rosterAssignmentPage = $request->getRosterAssignmentPage();
        $relatedPagesIds = $rosterAssignmentPageClass
            ->getMyRosterAssignmentPagesByRosterAssignmentId($roster_assignment_id)
            ->pluck('page_id')
            ->toArray();
        //we have reLoad the pages because the order its important here
        $relatedPagesIds = Page::whereIn('id',$relatedPagesIds)
            ->pluck('id')->toArray();


        if($user->account_type == 'student'){
            $user->load('Student');
            $page = Page::with(['RosterAssignmentPages'=>function($query) use($roster_assignment_id,$user){
                return $query->where('roster_assignment_id',$roster_assignment_id)
                    ->with(['RosterAssignmentStudentPages'=>function($query)use($user){
                        return $query->where('student_id',$user->Student->id);
                    }]);
            }])->findOrFail($page_id);

        }elseif($user->account_type == 'parent'){
            $student = UserServices::getTargetedStudentByParent($request->student_id);

            $page = Page::with(['RosterAssignmentPages'=>function($query) use($roster_assignment_id,$student){
                return $query->where('roster_assignment_id',$roster_assignment_id)
                    ->with(['RosterAssignmentStudentPages'=>function($query)use($student){
                        return $query->where('student_id',$student->id);
                    }]);
            }])
                ->findOrFail($page_id);
        }else{
            $page = Page::with(['RosterAssignmentPages'=>function($query) use($roster_assignment_id){
                return $query->where('roster_assignment_id',$roster_assignment_id);
            }])->findOrFail($page_id);
        }


        $assignment = Assignment::findOrFail($page->assignment_id);
        $rosterAssignment = RosterAssignment::findOrFail($roster_assignment_id);//because the front need rosterAssignment Settings
        $userOfAssignmentOwner = AssignmentServices::getAssignmentOwner($assignment);


        $image = null;
        if(isset($page->page)){
            $imagePath = public_path($page->getRawOriginal('page'));
            $image = "data:image/png;base64,".base64_encode(file_get_contents($imagePath));
        }

        return ApiResponseClass::successResponse([
            'page'=>new PageResource($page),
            'image' => $image,
            'roster_assignment'=> new RosterAssignmentResource($rosterAssignment),
            'owner_assignment_user' => $userOfAssignmentOwner,
            'related_pages_ids' => $relatedPagesIds,
        ]);
    }



    public function hideAndUnHide(HideOrUnHideRosterAssignmentPageRequest $request,$roster_assignment_id,$page_id){
        $rosterAssignmentPage = $request->getRosterAssignmentPage();
        $rosterAssignmentPage->hideOrUnHide();

        return ApiResponseClass::successMsgResponse();
    }

    public function lockOrUnLock(LockOrUnLockRosterAssignmentPageRequest $request,$roster_assignment_id,$page_id){
        $rosterAssignmentPage = $request->getRosterAssignmentPage();
        $rosterAssignmentPage->lockOrUnLock();

        return ApiResponseClass::successMsgResponse();
    }


}
