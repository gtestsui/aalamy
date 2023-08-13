<?php


namespace App\Modules\User\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Address\Http\DTO\AddressData;

final class SchoolData extends ObjectData
{
    public ?int         $id=null;
    public  string      $school_name;
    public ?string      $bio;
    public ?string      $school_image;
//    public bool      $is_active;
    public ?bool      $allow_student_request;
    public ?bool      $allow_teacher_request;

//    public ?AddressData $addressData;
    public ?int  $school_country_id;
    public ?int  $school_state_id;
//    public ?int  $school_city_id;
    public ?string  $school_city;
    public ?string  $school_street;
//    public ?int  $school_address_id_for_update;

    public static function fromRequest(Request $request): self
    {

        $schoolImagePath = null;
        if(isset($request->school_image))
            $schoolImagePath = FileManagmentServicesClass::storeBase64File($request->school_image,"school/{$request->school_name}",$request->school_name);

//        $schoolImagePath = FileManagmentServicesClass::storeFiles($request->school_image,"school/{$request->school_name}",$request->school_name);
//            $schoolImagePath = ServicesClass::storeFiles($request->school_image,"school/{$request->school_name}",$request->school_name);

//        $addressData = null;
//        if(isset($request->school_country_id))
//            $addressData = AddressData::fromRequest($request);

        return new self([
            'school_name' => $request->school_name,
            'bio' => $request->bio,
            'school_image' => $schoolImagePath,
            'allow_student_request' => isset($request->allow_student_request)?(bool)$request->allow_student_request:null,
            'allow_teacher_request' => isset($request->allow_teacher_request)?(bool)$request->allow_teacher_request:null,
//            'addressData' => $addressData,

            'school_country_id' => isset($request->school_country_id)?(int)$request->school_country_id:null,
            'school_state_id'   => isset($request->school_state_id)?(int)$request->school_state_id:null,
//            'school_city_id'    => isset($request->school_city_id)?(int)$request->school_city_id:null,
            'school_city'    => $request->school_city,
            'school_street'     => $request->school_street,

//            'school_address_id_for_update' => isset($request->school_address_id_for_update)?(int)$request->school_address_id_for_update:null,


        ]);
    }

}
