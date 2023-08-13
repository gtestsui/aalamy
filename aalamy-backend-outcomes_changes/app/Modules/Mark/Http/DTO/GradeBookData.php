<?php


namespace Modules\Mark\Http\DTO;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;

final class GradeBookData extends ObjectData
{
    public ?int      $id = null;
    public string    $grade_book_name;
    public int       $roster_id;
    public int       $level_subject_id;
//    public array     $roster_assignments_ids;
    public array     $roster_assignments_weights;//array of key-value (key is assignment_id and value it's the weight)
//    public array     $quizzes_ids;
    public array     $quizzes_weights;//array of key-value (key is quiz_id and value it's the weight)
    public int       $external_marks_weight;


    public FilterRosterAssignmentData $filter_roster_assignment_data;
    public FilterQuizData $filter_quiz_data;
    public array $external_marks;


    public static function fromRequest(Request $request): self
    {

        $user = $request->user();


//        $allWeights = 0;
        $filterRosterAssignmentArray = [];
        $rosterAssignmentsWeights = [];
        $filterRosterAssignmentArray['roster_assignment_ids'] = [-1];//we have pushed -1 because when the array empty will return all roster assignments
        if(isset($request->roster_assignments)){
            foreach ($request->roster_assignments as $rosterAssignmentInfo){
                $rosterAssignmentIds[] = $rosterAssignmentInfo['id'];
                $rosterAssignmentsWeights[$rosterAssignmentInfo['id']] = $rosterAssignmentInfo['weight'];
//                $allWeights+= $rosterAssignmentInfo['weight'];
            }
            $filterRosterAssignmentArray['roster_assignment_ids'] = $rosterAssignmentIds;
        }
        $filterRosterAssignmentArray['level_subject_id'] = $request->level_subject_id;
        $filterRosterAssignmentData = FilterRosterAssignmentData::fromArray($filterRosterAssignmentArray);


        $filterQuizArray = [];
        $quizzesWeights = [];
        $filterQuizArray['quizzes_ids'] = [-1];//we have pushed -1 because when the array empty will return all quizzes
        if(isset($request->quizzes)){
            foreach ($request->quizzes as $quizInfo){
                $quizzesIds[] = $quizInfo['id'];
                $quizzesWeights[$quizInfo['id']] = $quizInfo['weight'];
//                $allWeights+= $quizInfo['weight'];
            }
            $filterQuizArray['quizzes_ids'] = $quizzesIds;

        }
        $filterQuizArray['level_subject_id'] = $request->level_subject_id;
        $filterQuizData = FilterQuizData::fromArray($filterQuizArray);

//        if(isset($request->external_marks_weight)){
//            $allWeights+= $request->external_marks_weight;
//
//        }
//        if($allWeights != 100)
//            throw new ErrorMsgException('the total weight should be 100');
//

        return new self([
            'grade_book_name'    => $request->grade_book_name,
            'roster_id'          => (int)$request->roster_id,
            'level_subject_id'          => (int)$request->level_subject_id,
            'roster_assignments_weights' => $rosterAssignmentsWeights,
            'quizzes_weights'=> $quizzesWeights,

            'external_marks_weight'        => isset($request->external_marks_weight)
                ?(int)$request->external_marks_weight
                :0,

            'filter_roster_assignment_data' => $filterRosterAssignmentData,
            'filter_quiz_data' => $filterQuizData,
            'external_marks' => isset($request->external_marks)?$request->external_marks:[],


        ]);
    }


}
