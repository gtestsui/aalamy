<?php


namespace Modules\SchoolEmployee\Http\Controllers\Classes\ManageEmployee;


use App\Exceptions\ErrorMsgException;
use App\Modules\User\Http\DTO\EducatorData;
use Illuminate\Foundation\Http\FormRequest;
use Modules\SchoolEmployee\Models\SchoolEmployee;
use Modules\User\Http\Controllers\Classes\EducatorClass;
use Modules\User\Http\Controllers\Classes\UserClass;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class TeacherEmployeeClass extends BaseManageEmployeeAbstract
{


    public function create(FormRequest $request){
        $teacher = $this->storeTeacher($request);
        $schoolEmployeeData = $this->initializeSchoolEmployeeDTO($request,$teacher);
//        $schoolEmployeeData->merge([
//            'teacher_id' => $teacher->id
//        ]);
        $this->storeSchoolEmployee($schoolEmployeeData);

    }

    public function createFoundTeacher(FormRequest $request,Teacher $teacher){
        $schoolEmployeeData = $this->initializeSchoolEmployeeDTO($request,$teacher,false);
//        $teacher = $this->storeTeacher($schoolEmployeeData,$request);
//        $schoolEmployeeData->merge([
//            'teacher_id' => $teacher->id
//        ]);
        $this->storeSchoolEmployee($schoolEmployeeData);

    }

    /**
     * @param $request
     * @return Teacher
     * @throws \App\Exceptions\ErrorMsgException
     */
    private function storeTeacher($request){
        $userData = UserData::fromRequest($request,'educator');
        $educatorData = EducatorData::fromRequest($request,$userData);
        $educatorClass = new EducatorClass();
        $user = $educatorClass->create($educatorData,$userData);
        return Teacher::where('user_id',$user->id)->firstOrFail();

    }

    public function update(SchoolEmployee $schoolEmployee,FormRequest $request){
        $teacher = Teacher::findOrFail($schoolEmployee->teacher_id);
        $user = User::findOrFail($teacher->user_id);
        $userData = UserData::fromRequest($request,'educator');
        $educatorClass = new EducatorClass();
        $educatorData = $educatorClass->getDataFromRequest($request,$userData);
        $targetUser = $educatorClass->updateAccountWithPersonalInfo($educatorData,$userData,$user);
//        throw new ErrorMsgException(json_encode($educatorData->all()));
        parent::update($schoolEmployee,$request);
    }

}
