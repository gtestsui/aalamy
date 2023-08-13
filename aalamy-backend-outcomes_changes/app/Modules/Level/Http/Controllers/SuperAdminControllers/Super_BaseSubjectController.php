<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Level\Http\Requests\BaseSubject\StoreBaseSubjectRequest;
use Modules\Level\Http\Requests\baseSubject\UpdateBaseSubjectRequest;
use Modules\Level\Http\Resources\BaseSubjectResource;
use Modules\Level\Models\BaseSubject;

class Super_BaseSubjectController extends Controller
{


    public function index(){
        $subjects = BaseSubject::with('BaseSubject')->get();
        return ApiResponseClass::successResponse(
            BaseSubjectResource::collection($subjects)
        );
    }

    public function baseSubjectsDoesntBelongToBaseLevel($base_level_id){
        $subjects = BaseSubject::whereDoesntHave('BaseLevelSubjects',function($query)use ($base_level_id){
            return $query->where('base_level_id',$base_level_id);
        })->get();

        return ApiResponseClass::successResponse(
            BaseSubjectResource::collection($subjects)
        );
    }

    public function root(){
        $subjects = BaseSubject::whereNull('base_subject_id')->get();
        return ApiResponseClass::successResponse(
            BaseSubjectResource::collection($subjects)
        );
    }

    public function store(StoreBaseSubjectRequest $request){
        $arrayForCreate = [
            'name' => $request->name,
            'semester' => null,
            'code' => $request->code,
            'hyperlink' => $request->hyperlink,
            'base_subject_id' => $request->base_subject_id,
        ];
        if($request->first_semester){
            $arrayForCreate['semester'] = 1;
            BaseSubject::create($arrayForCreate);
        }
        if($request->second_semester){
            $arrayForCreate['semester'] = 2;
            BaseSubject::create($arrayForCreate);
        }
        return ApiResponseClass::successMsgResponse();
//        $subject = BaseSubject::create([
//            'name' => $request->name,
//            'semester' => $request->semester,
//            'code' => $request->code,
//            'hyperlink' => $request->hyperlink,
//            'base_subject_id' => $request->base_subject_id,
//        ]);
//        return ApiResponseClass::successResponse($subject);
    }

    public function update(UpdateBaseSubjectRequest $request,$id){
        $subject = BaseSubject::find($id);
        $subject->update([
            'name' => $request->name,
//            'semester' => $request->semester,
            'code' => $request->code,
            'hyperlink' => $request->hyperlink,
            'base_subject_id' => $request->hyperlink==1?$request->base_subject_id:null,
        ]);
        return ApiResponseClass::successResponse($subject);
    }




}
