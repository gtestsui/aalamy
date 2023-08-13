<?php


namespace Modules\SchoolEmployee\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\User\Http\Controllers\Classes\UserServices;

final class SchoolEmployeeData extends ObjectData
{
    public ?int      $id=null;
    public int          $school_id;
    public ?int         $teacher_id;
    public ?string      $fname;
    public ?string      $lname;
    public ?string      $father_name;
    public ?string      $mother_name;
    public ?string      $grandfather_name;
    public ?string      $place_of_birth;
    public ?Carbon      $date_of_birth;
    public ?string      $gender;
    public ?string      $original_state;
    public ?string      $place_of_registration;
    public ?string      $number_of_registration;
    public ?string      $nationality;
    public ?string      $phone_code;
    public ?string      $phone_iso_code;
    public ?string      $phone_number;
    public ?string      $identifier_number;
    public ?string      $address;
    public ?string      $marriage_state;
    public ?string      $job_info;
    public ?string      $experience;
    public ?string      $computer_skills;
    public ?bool        $added_manually_by_school;
    public ?string      $type;
    /**
     * @var array<int,UploadedFile|array
     */
    public        $certificates_images;
    /**
     * @var array<int,UploadedFile|array
     */
    public        $certificates_files;
    /** @var array<int,int> */
    public        $certificates_ids_for_delete;


////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,bool $forUpdate=false): self
    {
        $user = $request->user();
        list(,$school) = UserServices::getAccountTypeAndObject($user);

        return new self([
            'school_id' => !$forUpdate?$school->id:null,
//            'teacher_id' => null,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'grandfather_name' => $request->grandfather_name,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'date_of_birth' => parent::generateCarbonObject($request->date_of_birth,true),
            'original_state' => $request->original_state,
            'place_of_registration' => $request->place_of_registration,
            'number_of_registration' => $request->number_of_registration,
            'nationality' => $request->nationality,
            'phone_code' => isset($request->phone_code)?$request->phone_code:null,
            'phone_iso_code' => isset($request->phone_iso_code)?strtolower($request->phone_iso_code):null,
            'phone_number' => isset($request->phone_number)?$request->phone_number:null,
            'identifier_number' => $request->identifier_number,
            'address' => $request->address,
            'marriage_state' => $request->marriage_state,
            'job_info' => $request->job_info,
            'experience' => $request->experience,
            'computer_skills' => $request->computer_skills,
//            'added_manually_by_school' => true,
            'type' => $request->type,
            'certificates_images' => isset($request->certificates_images)?$request->certificates_images:[],
            'certificates_files' => isset($request->certificates_files)?$request->certificates_files:[],
            'certificates_ids_for_delete' => isset($request->certificates_ids_for_delete)?$request->certificates_ids_for_delete:[],

        ]);
    }

}
