<?php

namespace Modules\User\Imports;

use App\Http\Controllers\Classes\ApiResponseClass;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Models\Level;
use Modules\Outcomes\Http\Controllers\Classes\OutcomesServices;
use Modules\Setting\Models\YearSetting;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class SchoolStudentImport implements /*ToModel*/ToCollection,SkipsEmptyRows,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private User $schoolUser ;
    private School $school ;
    private Teacher $teacher ;
    private $classId ;
    private $createdUserEmailsAndPasswords = [] ;
    public function __construct(User $user,$classId)
    {
//        $this->user = $user;
        if($user->account_type == 'educator' && isset(request()->my_teacher_id)){
            list(,$this->teacher) = UserServices::getAccountTypeAndObject($user);
            $this->school = School::findOrFail($this->teacher->school_id);
            $this->schoolUser = User::findOrFail($this->school->user_id);
        }else{

            $this->schoolUser = $user->load('School');
            $this->school = $user->School;
        }
        $this->classId = $classId;

    }


    public function collection(Collection $rows)
    {

        $studentCountModuleClass = StudentCountModuleClass::createByOwner($this->schoolUser);
        $studentCountModuleClass->checkForImport($rows->count());
        $yearSetting = YearSetting::first();

        foreach ($rows as $key=>$row)
        {

            $validationResult = \Validator::make($row->toArray(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'phone_number' => 'nullable',
                'phone_code' => 'required_with:*.phone_number',
                'country_code' => 'required_with:*.phone_numbe',
                'gender' => ['required',Rule::in(config('User.panel.genders'))],
                'date_of_birth' => 'required|date_format:'.config('panel.date_format'),
                'type' => ['required',Rule::in('kid','young')],
                'parent_email' => 'nullable|email|unique:students,parent_email',
            ]);
            if($validationResult->fails()) {
                throw new HttpResponseException(
                    ApiResponseClass::validateResponse($validationResult->errors())
                );
            }


            $studentUser = User::withoutEvents(function ()use ($row){
                return User::create([
                    'fname' => $row['first_name'],
                    'lname' => $row['last_name'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'verified_status' => 1,
                    'account_type' => 'student',
                    'phone_code' => isset($row['phone_code'])?$row['phone_code']:null,
                    'phone_iso_code' => isset($row['country_code'])?$row['country_code']:null,
                    'phone_number' => isset($row['phone_number'])?$row['phone_number']:null,
                    'gender' => $row['gender'],
                    'date_of_birth' => $row['date_of_birth'],
                    'unique_username' => UserServices::generateUniqueGuide(),
                ]);
            });

            $this->createdUserEmailsAndPasswords[] = [
                'email' => $studentUser->email,
                'password' => $row['password'],
            ];

            $student = Student::create([
                'user_id' => $studentUser->id,
                'type' => $row['type'],
                'parent_email' => $row['type']=='kid'
                    ?$row['email']
                    :$row['parent_email'],
//                'parent_code' => UserServices::generateParentCode(),
                'parent_code' => ConfirmationAccountServices::generateParentCode(),
                'created_by_school' => $this->school->id,
                'created_by_teacher' => isset($this->teacher)?$this->teacher->id:null,
            ]);

            SchoolStudent::create([
                'student_id' => $student->id,
                'school_id' => $this->school->id,
                'start_date' => Carbon::now(),
            ]);

            ClassStudent::create([
               'class_id' => $this->classId,
               'student_id' => $student->id,
               'school_id' => $this->school->id,
               'teacher_id' => isset($this->teacher)?$this->teacher->id:null,
               'study_year' => $yearSetting->start_date,
            ]);

        	$classModle = ClassModel::with('Level')->findOrFail($this->classId);
            // $level = Level::findOrFail($this->classId);
            OutcomesServices::initialize(
                $student->id,
                $this->school->id,
                $classModle->Level,
                $yearSetting
            );

        }

    }

    public function getCreatedEmailsAndPasswords(){
        return $this->createdUserEmailsAndPasswords;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }



}
