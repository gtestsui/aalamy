<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\DTO\StudentData;
use Illuminate\Http\Request;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\StudentBasicInformation;
use Modules\User\Models\StudentFamilyInformation;
use Modules\User\Models\StudentOtherInformation;
use Modules\User\Models\StudentSocialAndPersonalInformation;
use Modules\User\Models\User;

class StudentClass extends UserClass
{

    public function getDataFromRequest(Request $request,UserData $userData=null):StudentData
    {
        $data = StudentData::fromRequest($request);
        return $data;
    }

    /**
     * $withObserve when true to send verification code
     * @param StudentData $studentData
     * @param UserData $userData
     * @return User
     */
    public function create(StudentData $studentData, UserData $userData):User
    {
        $withObserve = false;
        if(is_null($studentData->created_by_teacher)&&is_null($studentData->created_by_school)&&is_null($studentData->created_by_educator))
            $withObserve = true;
        $user = Parent::createUser($userData,$withObserve);
        if($studentData->type == 'kid'){
            //the parent email is the same email
            $parentEmail = $user->email;
        }elseif($studentData->type == 'young'){
            //the parent email is not the same email
            $parentEmail = $studentData->parent_email;

        }
        $student = Student::create([
            'type' => $studentData->type,
            'parent_email' => $parentEmail,
            'user_id' => $user->id,
            'parent_code' => $studentData->parent_code,
            'created_by_teacher' => $studentData->created_by_teacher,
            'created_by_school' => $studentData->created_by_school,
            'created_by_educator' => $studentData->created_by_educator,
        ]);
//        StudentBasicInformation::create(array_merge($studentData->basicInformation,['student_id'=>$student->id]));
//        StudentFamilyInformation::create(array_merge($studentData->familyInformation,['student_id'=>$student->id]));
//        StudentOtherInformation::create(array_merge($studentData->otherInformation,['student_id'=>$student->id]));
//        StudentSocialAndPersonalInformation::create(array_merge($studentData->socialAndPersonalInformation,['student_id'=>$student->id]));
        $user->load('Student');

        return $user;
    }

    public function createStudentAllInformation(StudentData $studentData,Student $student){
        StudentBasicInformation::create(array_merge($studentData->basicInformation,['student_id'=>$student->id]));
        StudentFamilyInformation::create(array_merge($studentData->familyInformation,['student_id'=>$student->id]));
        StudentOtherInformation::create(array_merge($studentData->otherInformation,['student_id'=>$student->id]));
        StudentSocialAndPersonalInformation::create(array_merge($studentData->socialAndPersonalInformation,['student_id'=>$student->id]));
    }

    public function  update(StudentData $studentData, User $user): User
    {
        $user->load('Student');
        if($user->Student->type == 'kid'){
            //the parent email is the same email
            $parentEmail = $user->email;
        }elseif($user->Student->type == 'young'){
            //the parent email is not the same email
            $parentEmail = $studentData->parent_email;

        }
        $student = $user->Student;
        $student->update([
            'parent_email' => $parentEmail,
        ]);
//        $user->refresh();

        return $user;
    }

    public function updateAccountWithPersonalInfo(StudentData $studentData, UserData $userData,User $user):User
    {
        $user = Parent::updateUser($userData,$user);
        $user = $this->update($studentData,$user);
        return  $user;

    }

    /**
     * @return SchoolStudent
     */
    public function getMyActiveSchoolStudent(Student $student){
        $schoolStudent = SchoolStudent::where('student_id',$student->id)
            ->active()->first();
        return $schoolStudent;
    }

    /**
     * @return SchoolStudent
     */
    public function getMySchoolStudent(Student $student){
        $schoolStudent = SchoolStudent::where('student_id',$student->id)
            ->with('School')
            ->get();
        return $schoolStudent;
    }

    /**
     * @return EducatorStudent
     */
    public function getMyActiveEducatorStudent(Student $student){
        $myEducatorStudents = EducatorStudent::where('student_id',$student->id)
            ->active()->get();

        return $myEducatorStudents;
    }

    /**
     * @return EducatorStudent
     */
    public function getMyEducatorStudent(Student $student){
        $myEducatorStudents = EducatorStudent::where('student_id',$student->id)
            ->with('Educator.User')
            ->get();

        return $myEducatorStudents;
    }


    public function updateStudentInformation(StudentData $studentData,User $user){
        $user->load('Student');
        $student = $user->Student;
        $this->updateStudentBasicInformation($studentData,$student,$user);
        $this->updateStudentFamilyInformation($studentData,$student);
        $this->updateStudentOtherInformation($studentData,$student);
        $this->updateStudentSocialAndPersonalInformation($studentData,$student);

    }

    public function updateStudentBasicInformation(StudentData $studentData,Student $student,User $user){
        if(isset($studentData->basicInformation['place_of_birth_image'])){
            //if isset => new picture or the same old picture(as link)
            if(!str_contains($studentData->basicInformation['place_of_birth_image'],'http')){
                $studentData->basicInformation['place_of_birth_image'] = FileManagmentServicesClass::storeBase64File($studentData->basicInformation['place_of_birth_image'],'student-basic-information/place-of-birth',$user->getFullName());
            }
        }else{
            $studentData->basicInformation['place_of_birth_image'] = null;
        }

        StudentBasicInformation::where('student_id',$student->id)
            ->update($studentData->basicInformation);

    }

    public function updateStudentFamilyInformation(StudentData $studentData,Student $student){

        StudentFamilyInformation::where('student_id',$student->id)
            ->update($studentData->familyInformation);

    }

    public function updateStudentOtherInformation(StudentData $studentData,Student $student){

        StudentOtherInformation::where('student_id',$student->id)
            ->update($studentData->otherInformation);

    }

    public function updateStudentSocialAndPersonalInformation(StudentData $studentData,Student $student){

        StudentSocialAndPersonalInformation::where('student_id',$student->id)
            ->update($studentData->socialAndPersonalInformation);

    }

}
