<?php

namespace Modules\Assignment\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Assignment\Http\Controllers\Classes\AssignmentPageServices;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Http\Requests\Page\CreatePageRequest;
use Modules\Assignment\Http\Requests\Page\UpdatePagesOrderRequest;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentPageServices;
use Modules\Assignment\Http\DTO\PageData;
use Modules\Assignment\Http\Requests\Page\CreateEmptyPageRequest;
use Modules\Assignment\Http\Requests\Page\DestroyPageRequest;
use Modules\Assignment\Http\Requests\Page\HideOrUnHidePageRequest;
use Modules\Assignment\Http\Requests\Page\LockOrUnLockPageRequest;
use Modules\Assignment\Http\Requests\Page\ShowPageRequest;
use Modules\Assignment\Http\Resources\PageResource;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;
use Modules\RosterAssignment\Models\RosterAssignment;

class PageController extends Controller
{


    public function createPage(CreateEmptyPageRequest $request){
        $pageData = PageData::fromRequest($request);
        DB::beginTransaction();
        if(isset($pageData->page)){
            $assignment = $request->getAssignment();
            if (isset($request->page)){
                $page = AssignmentPageServices::addPageAsLinkToAssignment(
                    $request->page,$assignment
                );
            }
        }else{
            $page = Page::create($pageData->all());

        }


        RosterAssignmentPageServices::addEmptyPageToRosterAssignmentsContainsThisAssignment(
            $page,$request->assignment_id
        );
        //for initialize the new page in all assigned rosters
        $rosterIds = RosterAssignment::where('assignment_id',$request->assignment_id)->pluck('roster_id')->toArray();
        RosterAssignmentStudentPageServices::addStudentPages($request->assignment_id,$rosterIds,$page);

        DB::commit();
        return ApiResponseClass::successResponse(new PageResource($page));

    }


    public function show(ShowPageRequest $request,$assignment_id,$page_id){
//        $page = Page::findOrFail($page_id);
        $page = $request->getPage();

        $pagesIds = Page::where('assignment_id',$page->assignment_id)->pluck('id')->toArray();

        $assignment = Assignment::findOrFail($page->assignment_id);
        $userOfAssignmentOwner = AssignmentServices::getAssignmentOwner($assignment);

        $image = null;
        if(isset($page->page)){
            $imagePath = public_path($page->getRawOriginal('page'));
            $image = "data:image/png;base64,".base64_encode(file_get_contents($imagePath));
        }


        return ApiResponseClass::successResponse([
            'page'=>new PageResource($page),
            'image' => $image,
            'owner_assignment_user' => $userOfAssignmentOwner,
            'related_pages_ids' => $pagesIds,
        ]);
    }

    public function updatePagesOrder(UpdatePagesOrderRequest $request,$assignment_id){

        foreach ($request->pages as $pageData){
            Page::where('assignment_id',$assignment_id)
                ->where('id',$pageData['id'])
                ->update([
                    'order'=>$pageData['order']
                ]);
        }
        return ApiResponseClass::successMsgResponse();
    }


    public function hideAndUnHide(HideOrUnHidePageRequest $request,$page_id){
        $page = $request->getPage();
        $page->hideOrUnHide();

        return ApiResponseClass::successMsgResponse();
    }

    public function lockOrUnLock(LockOrUnLockPageRequest $request,$page_id){
        $page = $request->getPage();
        $page->lockOrUnLock();

        return ApiResponseClass::successMsgResponse();
    }



    public function softDelete(DestroyPageRequest $request,$id){
        DB::beginTransaction();
//        $page = Page::findOrFail($id);
        $page = $request->getPage();

        $page->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
