<?php


namespace Modules\Notification\Http\Controllers\Classes\RosterAssignment;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\Quiz\Models\Quiz;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class NewRosterAssignmentRequestNotification extends NotificationClass
{
    public  $typeNum = 3;
    public  $assignmentIds;
    public  $rosterIds;
    private $quiz;
    private $fromUser;

    public function __construct($assignmentIds,$rosterIds,User $fromUser)
    {
        if(!is_array($assignmentIds))
            $assignmentIds = [$assignmentIds];
        if(!is_array($rosterIds))
            $rosterIds = [$rosterIds];

        $this->assignmentIds = $assignmentIds;
        $this->rosterIds = $rosterIds;

        $this->fromUser = $fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_assignment_assigned',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'there is a new assignment from ' .$fromUser->getFullName();
        $this->notificationTitle_ar = 'تمت اضافة وظيفة جديدة من قبل '.
            $fromUser->getFullName();
        $this->notificationBody = 'there is a new assignment from '.$fromUser->getFullName();
        $this->notificationBody_ar = ' تمت اضافة وظيفة جديدة من قبل '.
            $fromUser->getFullName();

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'quiz' => $this->quiz,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notify(){
        $rosterAssignments = RosterAssignment::whereIn('roster_id',$this->rosterIds)
            ->whereIn('assignment_id',$this->assignmentIds)
            ->get();

        $rosters = Roster::whereIn('id',$this->rosterIds)
            ->with('RosterStudents.ClassStudent.Student.User.Firebase')
            ->get();

        foreach ($rosters as $roster){
            $arrayForCreate = [];
            foreach ($this->assignmentIds as $assignmentId){
                $rosterAssignment = $rosterAssignments
                    ->where('roster_id',$roster->id)
                    ->where('assignment_id',$assignmentId)
                    ->first();
                $data = [
                    'notificationData' =>[
                        'roster_assignment' => $rosterAssignment,
                    ],

                ];
                $body = array_merge($data,$this->getNotficationBody());
                $userIds = [];
                foreach ($roster->RosterStudents as $rosterStudent){
                    $user = $rosterStudent->ClassStudent->Student->User;
                    $userIds[] = $user->id;
                    $arrayForCreate [] = [
                        'type_id' => $this->notificationType->id,
                        'user_id' => $user->id,
                        'data'    => json_encode($body),
                        'created_at' => Carbon::now()
                    ];

                }
                Parent::toFireBase(
                    $userIds,
                    $body,
                    $this->notificationTitle,
                    $this->notificationBody);

            }
            Notification::insert($arrayForCreate);

        }

    }

//    public function toFireBaseByTokens(array $tokens, array $notificationInfo,$title,$alarm){
//        if(count($tokens) <= 0 )
//            return 0;
//
//        $data = [
//            "registration_ids" => $tokens,
////            "notification" => [
////                "title" => $title,
////                "description" => [
////                        'Alarm'=>$alarm,
////                ],
////                'sound' => 1,
//////                'image' => 'Your image link here',
////            ],
//            "data" => $notificationInfo,
//        ];
//        $dataString = json_encode($data);
//
//        $headers = [
//            'Authorization: key=' . config('services.firebase.server_api_key'),
//            'Content-Type: application/json',
//        ];
//
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
//        $response = curl_exec($ch);
//    }
//


    public function notifyFor(){



        //We Can Do (this is better solution)

        $classStudentQuery = RosterStudent::where('roster_id',$this->quiz->roster_id)
            ->whereColumn('roster_students.class_student_id','class_students.id')
            ->getQuery();
        $studentQuery = ClassStudent::addWhereExistsQuery($classStudentQuery)
            ->whereColumn('class_students.student_id','students.id')
            ->getQuery();
        $toUserIds = Student::addWhereExistsQuery($studentQuery)->pluck('user_id')->toArray();


        //Or We Can Do

//        $classStudentIds = RosterStudent::where('roster_id',$this->quiz->roster_id)->pluck('class_student_id')->toArray();
//        $studentIds = ClassStudent::whereIn('id',$classStudentIds)->pluck('student_id')->toArray();
//        $toUserIds = Student::whereIn('id',$studentIds)->pluck('user_id')->toArray();


        //Or We Can Do
//        Student::whereHas('ClassStudents',function ($query){
//            return $query->whereHas('RosterStudents',function ($query){
//                return $query->where('roster_id',1);
//            });
//        })->pluck('user_id');


        $this->toUserIds = $toUserIds;
        return $this->toUserIds;
    }

    public function notifyToFirebase(){

        parent::toFireBase($this->toUserIds,$this->body(),$this->notificationTitle,$this->notificationBody);
    }

    public function notifyToDataBase(){
        $this->notifyUsersToDataBase();

        return true;
    }

}
