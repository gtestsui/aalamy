<?php


namespace Modules\SchoolEmployee\Http\Controllers\Classes\ManageEmployee;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Modules\SchoolEmployee\Http\DTO\SchoolEmployeeData;
use Modules\SchoolEmployee\Models\SchoolEmployee;
use Modules\SchoolEmployee\Models\SchoolEmployeeCertificate;
use Modules\User\Models\Teacher;

abstract class BaseManageEmployeeAbstract
{


    /**
     * @param FormRequest $request
     * @param bool $theTeacherAddedManualBySchool
     * @param Teacher|null $teacher if its found that mean we are creating teacher schoolEmployee
     * @return SchoolEmployeeData
     */
    public function initializeSchoolEmployeeDTO(FormRequest $request,?Teacher $teacher=null,bool $theTeacherAddedManualBySchool=true){
        $schoolEmployeeData = SchoolEmployeeData::fromRequest($request);
        $schoolEmployeeData->merge([
            'added_manually_by_school' => $theTeacherAddedManualBySchool
        ]);
        if(isset($teacher)){
            $schoolEmployeeData->merge([
                'teacher_id' => $teacher->id
            ]);
        }
        return $schoolEmployeeData;
    }

    public function create(FormRequest $request){
        $schoolEmployeeData = $this->initializeSchoolEmployeeDTO($request);
        $this->storeSchoolEmployee($schoolEmployeeData);

    }

    /**
     * @param SchoolEmployeeData $schoolEmployeeData
     * @return SchoolEmployee
     */
    public function storeSchoolEmployee(SchoolEmployeeData $schoolEmployeeData){
        $schoolEmployee =   SchoolEmployee::create($schoolEmployeeData->all());

        $this->storeCertificates($schoolEmployeeData->certificates_images,$schoolEmployee->id,'picture');
        $this->storeCertificates($schoolEmployeeData->certificates_files,$schoolEmployee->id,'pdf');

    }

    /**
     * @param array<int,UploadedFile>$certificates
     * @param mixed|int $schoolEmployeeId
     */
    public function storeCertificates($certificates,$schoolEmployeeId,$type){
        foreach ($certificates as $certificate){
            $path = FileManagmentServicesClass::storeFiles($certificate,'school/teachers/certificates');
            SchoolEmployeeCertificate::create([
                'school_employee_id' => $schoolEmployeeId,
                'certificate' => $path,
                'file_type' => $type,
            ]);
        }
    }


    public function update(SchoolEmployee $schoolEmployee,FormRequest $request){
        $schoolEmployeeData = $this->initializeSchoolEmployeeDTO($request);
        $schoolEmployee->update($schoolEmployeeData->initializeForUpdate());

        $this->storeCertificates($schoolEmployeeData->certificates_images,$schoolEmployee->id,'picture');
        $this->storeCertificates($schoolEmployeeData->certificates_files,$schoolEmployee->id,'pdf');
        $this->deleteCertificates($schoolEmployeeData->certificates_ids_for_delete,$schoolEmployee->id);

    }

    /**
     * @param array<int,UploadedFile>$certificates
     * @param mixed|int $schoolEmployeeId
     */
    public function deleteCertificates($certificates_ids,$schoolEmployeeId){
        SchoolEmployeeCertificate::where('school_employee_id',$schoolEmployeeId)
            ->whereIn('id',$certificates_ids)
            ->delete();
    }


}
