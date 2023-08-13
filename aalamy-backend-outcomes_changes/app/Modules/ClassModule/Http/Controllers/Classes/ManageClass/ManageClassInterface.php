<?php


namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;

interface ManageClassInterface
{


    public function myClasses();
    public function myClassesWithInfo();
    public function myClassesById($id);
    public function myClassesByIdOrFail($id);


}
