<?php


namespace Modules\QuestionBank\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\QuestionBank\Models\Question;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

final class MultiChoiceData extends ObjectData
{
    public ?int      $id=null;
    public string    $choice;
    public bool      $status;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,Question $question): self
    {
        $user = $request->user();


        return new self([
            'question_id'    => $question->id,
            'choice'    => $request->choice,
            'status'    => $request->status,
        ]);
    }

}
