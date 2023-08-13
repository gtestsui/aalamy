<?php


namespace Modules\Assignment\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Assignment\Http\Controllers\Classes\AssignmentPageServices;
use Modules\Assignment\Models\Assignment;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;
use Carbon\Carbon;

final class PageData extends ObjectData
{
    public ?int       $id=null;
    public int        $assignment_id;
    public ?string     $page;

    public bool      $is_locked;
    public bool      $is_hidden;
    public bool      $is_empty;


////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'assignment_id' => (int)$request->assignment_id,
            'page' => $request->page,

            'is_locked' =>isset($request->is_locked)
                            ?(bool)$request->is_locked
                            :config('Assignment.panel.assignment_page.is_locked_default'),

            'is_hidden' =>isset($request->is_hidden)
                            ?(bool)$request->is_hidden
                            :config('Assignment.panel.assignment_page.is_hidden_default'),

//            'is_empty' =>isset($request->is_empty)
//                ?(bool)$request->is_empty
//                :config('Assignment.panel.assignment_page.is_hidden_default'),

            'is_empty' =>isset($request->page)
                ?false
                :true,


        ]);
    }


}
