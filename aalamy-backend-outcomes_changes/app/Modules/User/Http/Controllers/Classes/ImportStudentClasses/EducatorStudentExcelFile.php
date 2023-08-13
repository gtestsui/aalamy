<?php

namespace Modules\User\Http\Controllers\Classes\ImportStudentClasses;

use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Notification\Jobs\StudentCreatedByOthers\SendPasswordToStudentCreatedByOtherNotification;
use Modules\User\Imports\EducatorStudentImport;
use Modules\User\Imports\SchoolStudentImport;
use Modules\User\Models\User;

class EducatorStudentExcelFile implements FileInterface
{



    public function validationRules():array
    {
        return [
            FileServices::getFileFieldName() => 'required|file|mimes:'.$this->mimesToString(),
        ];
    }

    public function mimes(){
        return [
            'xls',
            'xlsx',
        ];
    }


    /**
     * @param User $user is the user who trying to import the file
     * @param mixed $rosterId the roster we are trying to import inside it
     */
    public function import($file,User $user,$rosterId){
        FileManagmentServicesClass::storeFiles($file,'excel/imported-students/'.$user->getFullName(),"group-$rosterId");
        set_time_limit(0);
        $importClass = new EducatorStudentImport($user,$rosterId);
        Excel::import($importClass,$file);
        ServicesClass::dispatchJob(new SendPasswordToStudentCreatedByOtherNotification(
            $importClass->getCreatedEmailsAndPasswords()
        ));
    }

    public function mimesToString(){
        return implode(',',$this->mimes());
    }

}
