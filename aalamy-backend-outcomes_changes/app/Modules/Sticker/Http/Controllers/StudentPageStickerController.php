<?php

namespace Modules\Sticker\Http\Controllers;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage\RosterAssignmentPageManagementFactory;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\Sticker\Http\Requests\StudentStickerPage\AddStickerOnStudentPageRequest;
use Modules\Sticker\Http\Requests\StudentStickerPage\DeleteStickerFromStudentPageRequest;
use Modules\Sticker\Http\Requests\StudentStickerPage\GetStudentPageStickersByStudentAndPageRequest;
use Modules\Sticker\Http\Requests\StudentStickerPage\GetStudentPageStickersRequest;
use Modules\Sticker\Http\Resources\StudentPageStickerResource;
use Modules\Sticker\Models\StudentPageSticker;
use Modules\User\Models\Student;

class StudentPageStickerController extends Controller
{
    /**
     * @note we have used $student_user_id instead of student_id directly
     * because the front end in the node have user_id
     */

    public function getStudentsPagesStickers(GetStudentPageStickersRequest $request,$roster_assignment_id){

        $rosterAssignmentPageIds =  RosterAssignmentPage::where('roster_assignment_id',$roster_assignment_id)
            ->pluck('id')->toArray();

        $rosterAssignmentStudentPagesIds = RosterAssignmentStudentPage::whereIn('roster_assignment_page_id',$rosterAssignmentPageIds)
            ->pluck('id')->toArray();

        $studentPageStickers = StudentPageSticker::whereIn('roster_assignment_student_page_id',$rosterAssignmentStudentPagesIds)
            ->with('Sticker')
            ->get();
        return ApiResponseClass::successResponse(StudentPageStickerResource::collection($studentPageStickers));
    }


    public function getStudentPageStickersByStudentAndPage(GetStudentPageStickersByStudentAndPageRequest $request,$roster_assignment_id,$page_id,$student_user_id){
        $user = $request->user();

        $rosterAssignmentPageClass = RosterAssignmentPageManagementFactory::create($user);
        $rosterAssignmentPage = $rosterAssignmentPageClass->getMyRosterAssignmentPageByRosterAssignemtIdByPageId(
            $roster_assignment_id,$page_id
        );
        if(is_null($rosterAssignmentPage))
            throw new ErrorUnAuthorizationException();

        $student = Student::where('user_id',$student_user_id)->firstOrFail();
        $rosterAssignmentStudentPage = RosterAssignmentStudentPage::where('roster_assignment_page_id',$rosterAssignmentPage->id)
            ->where('student_id',$student->id)
            ->firstOrFail();

        $studentPageStickers = StudentPageSticker::where('roster_assignment_student_page_id',$rosterAssignmentStudentPage->id)
            ->with('Sticker')
            ->get();
        return ApiResponseClass::successResponse(StudentPageStickerResource::collection($studentPageStickers));
    }


    public function addStickerOnStudentPage(
        AddStickerOnStudentPageRequest $request,
        $roster_assignment_id,
        $page_id,
        $student_user_id,
        $sticker_id)
    {
        $user = $request->user();
        $student = Student::where('user_id',$student_user_id)->firstOrFail();

        $rosterAssignmentPageClass = RosterAssignmentPageManagementFactory::create($user);
        $rosterAssignmentPage = $rosterAssignmentPageClass->getMyRosterAssignmentPageByRosterAssignemtIdByPageId(
            $roster_assignment_id,$page_id
        );
        if(is_null($rosterAssignmentPage))
            throw new ErrorUnAuthorizationException();

        $rosterAssignmentStudentPage = RosterAssignmentStudentPage::where('roster_assignment_page_id',$rosterAssignmentPage->id)
            ->where('student_id',$student->id)
            ->firstOrFail();

        StudentPageSticker::create([
           'roster_assignment_student_page_id' =>  $rosterAssignmentStudentPage->id,
           'roster_assignment_id' =>  $roster_assignment_id,
           'sticker_id' =>  $sticker_id,
           'page_id' =>  $page_id,
           'student_id' =>  $student->id,
        ]);
        return ApiResponseClass::successMsgResponse();
    }

    public function deleteStickerFromStudentPage(
        DeleteStickerFromStudentPageRequest $request,
                                       $roster_assignment_id,
                                       $page_id,
                                       $student_user_id,
                                       $sticker_id)
    {
        $user = $request->user();
        $student = Student::where('user_id',$student_user_id)->firstOrFail();


        $rosterAssignmentPageClass = RosterAssignmentPageManagementFactory::create($user);
        $rosterAssignmentPage = $rosterAssignmentPageClass->getMyRosterAssignmentPageByRosterAssignemtIdByPageId(
            $roster_assignment_id,$page_id
        );
        if(is_null($rosterAssignmentPage))
            throw new ErrorUnAuthorizationException();

        $rosterAssignmentStudentPage = RosterAssignmentStudentPage::where('roster_assignment_page_id',$rosterAssignmentPage->id)
            ->where('student_id',$student->id)
            ->firstOrFail();

        $studentPageSticker = StudentPageSticker::where('roster_assignment_student_page_id',$rosterAssignmentStudentPage->id)
            ->where('sticker_id',$sticker_id)
            ->where('page_id',$page_id)
            ->where('student_id',$student->id)
            ->first();

        $studentPageSticker->delete();
        return ApiResponseClass::deletedResponse();
    }






}
