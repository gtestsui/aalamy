<?php


namespace Modules\User\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\User;

final class StudentData extends ObjectData
{
    public ?int      $id=null;
    public ?string    $type;
    public ?string   $parent_email;
    public ?string   $parent_code;
    public array     $basicInformation;
    public array     $familyInformation;
    public array     $socialAndPersonalInformation;
    public array     $otherInformation;
    public ?int      $created_by_teacher;
    public ?int      $created_by_school;
    public ?int      $created_by_educator;
//    public ?int      $user_id;
//    public ?UserData $user;
//    public string    $parent_code;
//    public bool      $is_active;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,?User $user=null): self
    {
        $created_by_teacher = null;
        $created_by_school = null;
        $created_by_educator = null;
        if(isset($user)){
            if(isset($request->my_teacher_id)){
                list(,$teacher) = UserServices::getAccountTypeAndObject($user);
                $school = School::findOrFail($teacher->school_id);
                $created_by_teacher = $teacher->id;
            }else{
                list(,$school) = UserServices::getAccountTypeAndObject($user);
                $created_by_school = $school->id;
            }
        }
    
    	if(isset($request->basic_information['place_of_birth_image'])){
            $placeOfBirthImage  = FileManagmentServicesClass::storeBase64File(
                $request->basic_information['place_of_birth_image'],'student-basic-information'
            );
        	$request->basic_information = array_merge($request->basic_information,['place_of_birth_image'=>$placeOfBirthImage]);
        }
        return new self([
            'type' => $request->type,
            'parent_email' => $request->parent_email,
            'parent_code' => ConfirmationAccountServices::generateParentCode(),
            'basicInformation' => isset($request->basic_information)?$request->basic_information:[],
            'familyInformation' => isset($request->family_information)?$request->family_information:[],
            'socialAndPersonalInformation' => isset($request->social_and_personal_information)?$request->social_and_personal_information:[],
            'otherInformation' => isset($request->other_information)?$request->other_information:[],
            'created_by_teacher' => $created_by_teacher,
            'created_by_school' => $created_by_school,
            'created_by_educator' => $created_by_educator,

        ]);
    }


}
