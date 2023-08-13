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
use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAttendanceServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\Educator;
use Modules\User\Models\Student;
use Modules\User\Models\User;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;


class EducatorStudentImport implements /*ToModel*/ToCollection,SkipsEmptyRows,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private User $user ;
    private Educator $educator ;
    private $rosterId ;
    private $classId ;
    private $createdUserEmailsAndPasswords = [] ;

    public function __construct(User $user,$rosterId)
    {
        $this->user = $user;
        $user->load('Educator');
        $this->educator = $user->Educator;

        $this->rosterId = $rosterId;
        $roster = Roster::with('ClassInfo')->findOrFail($this->rosterId);
        $this->classId = $roster->ClassInfo->class_id;

    }


    public function collection(Collection $rows)
    {

        $studentCountModuleClass = StudentCountModuleClass::createByOwner($this->user);
        $studentCountModuleClass->checkForImport($rows->count());


        $studentIds = [];
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
                    'phone_code' => $row['phone_code']??null,
                    'phone_iso_code' => $row['country_code']??null,
                    'phone_number' => $row['phone_number']??null,
                    'gender' => $row['gender'],
                    'date_of_birth' => $row['date_of_birth'],
                    'unique_username' => UserServices::generateUniqueGuide(),
                ]);
            });

            $this->createdUserEmailsAndPasswords[] = [
                'email' => $studentUser->email,
                'password' => $row['password'],
            ];

            $parentEmail = $row['email'];
            $parentEmail = isset($row['type'])&&$row['type']=='young'&&isset($row['parent_email']);
            $student = Student::create([
                'user_id' => $studentUser->id,
                'type' => $row['type'],
                'parent_email' => $parentEmail,
//                'parent_code' => UserServices::generateParentCode(),
                'parent_code' => ConfirmationAccountServices::generateParentCode(),
                'created_by_educator' => $this->educator->id,
            ]);
            $studentIds[] = $student->id;

            EducatorStudent::create([
                'student_id' => $student->id,
                'educator_id' => $this->educator->id,
                'start_date' => Carbon::now(),
            ]);

            $classStudent = ClassStudent::create([
               'class_id' => $this->classId,
               'student_id' => $student->id,
               'educator_id' => $this->educator->id,
               'study_year' => Carbon::now(),
            ]);

            /*$rosterStudent = */RosterStudent::create([
               'roster_id' => $this->rosterId,
               'class_student_id' => $classStudent->id,
            ]);



        }
    	RosterAssignmentStudentPageServices::addDefinedStudentPages(
            $this->rosterId,
            $studentIds
        );
        RosterAssignmentStudentAttendanceServices::addStudentsAtendancesByRoster(
            $this->rosterId,$studentIds
        );


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
