<?php


namespace App\Modules\User\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\DTO\UserData;

final class EducatorData extends ObjectData
{
    public ?int      $id=null;
    public ?string   $bio;
    public ?string   $certificate;
    public bool      $should_delete_certificate;
    public ?int      $school_id;
    public ?int      $created_by_school_id;

    public static function fromRequest(Request $request,UserData $userData=null): self
    {

        $fullName = getFullNameSeperatedByDash($userData->fname,$userData->lname);
        $certificatePath = null;
        if(isset($request->certificate))
            $certificatePath = FileManagmentServicesClass::storeBase64File($request->certificate,"educators_certificates/{$fullName}",$fullName);
//            $certificatePath = FileManagmentServicesClass::storeFiles($request->certificate,"educators_certificates/{$fullName}",$fullName);
//            $certificatePath = ServicesClass::storeFiles($request->certificate,"educators_certificates/{$fullName}",$fullName);

        $created_by_school_id = null;
        $user = $request->user();
        if(!is_null($user)){
            list(,$accountObject) = UserServices::getAccountTypeAndObject($user);
            $created_by_school_id = $accountObject->id;
        }

        return new self([
            'bio' => $request->bio,
            'certificate' => $certificatePath,
            'school_id' => isset($request->school_id)?(int)$request->school_id:null,
            'should_delete_certificate' => isset($request->should_delete_certificate)
                ?(bool)$request->should_delete_certificate
                :false,
            'created_by_school_id' => $created_by_school_id,
        ]);
    }

    public function initializeForUpdate(?ObjectData $data=null){
        $arrayUpdate = parent::initializeForUpdate($data);
        if($this->should_delete_certificate){
            $arrayUpdate['certificate']=null;
        }

        return $arrayUpdate;
    }

}
