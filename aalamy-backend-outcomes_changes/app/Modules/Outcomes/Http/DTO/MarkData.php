<?php


namespace Modules\Outcomes\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class MarkData extends ObjectData
{
    public ?int      $id=null;
    public ?float    $verbal;
    public ?float    $jobs_and_worksheets;
    public ?float    $activities_and_Initiatives;
    public ?float    $quiz;
    public ?float    $exam;
    public ?float    $final_mark;

////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request): self
    {

        return new self([
            'verbal' => isset($request->verbal)?(float)$request->verbal:null,
            'jobs_and_worksheets' => isset($request->jobs_and_worksheets)?(float)$request->jobs_and_worksheets:null,
            'activities_and_Initiatives' => isset($request->activities_and_Initiatives)?(float)$request->activities_and_Initiatives:null,
            'quiz' => isset($request->quiz)?(float)$request->quiz:null,
            'exam' => isset($request->exam)?(float)$request->exam:null,

        ]);
    }

}
