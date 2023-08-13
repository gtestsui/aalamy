<?php


namespace Modules\Notification\Http\Controllers\Classes;

use Carbon\Carbon;
use Mail;
use Modules\Notification\Models\FirebaseToken;
use Modules\Notification\Models\Notification;
use Modules\Notification\Models\NotificationType;


class NotificationClass
{

    protected $notificationType;
    protected $toUserIds = [];
    protected $userIdsWithTeacherIdsAsKeys = [];//because the teacher and educator may have the same userId

    protected $notificationTitle = '',
              $notificationTitle_ar = '',
              $notificationBody = '',
              $notificationBody_ar = '';
    protected $notificationImage = null;


    public function getNotificationType($type){
        return NotificationType::where('type_num',$type)->firstOrFail();
    }

    protected function getNotficationBody(){
        return [
            'click_action'=>'FLUTTER_NOTIFICATION_CLICK',
            'notificationType' => $this->notificationType->type_num,
            'notificationTitle' => $this->notificationTitle,
            'notificationBody' => $this->notificationBody,
            'notificationTitle_ar' => $this->notificationTitle_ar,
            'notificationBody_ar' => $this->notificationBody_ar,
            'notificationImage' => $this->notificationImage,
            'teacherId' => null,

        ];
    }

    protected function notifyUsersToDataBase(){
        $chunkedUserIds = array_chunk($this->toUserIds,500);

        foreach($chunkedUserIds as $chunk){
            $arrayForCreate = [];
            foreach($chunk as $userId){
                $arrayForCreate [] = [
                    'type_id' => $this->notificationType->id,
                    'user_id' => $userId,
                    'data'    => json_encode($this->body()),
                    'created_at' => Carbon::now()
                ];
            }
            Notification::insert($arrayForCreate);
        }
    }

    protected function notifyUsersAsTeachersToDataBase(){
        if(count($this->userIdsWithTeacherIdsAsKeys)){
            $arrayForCreate = [];
            $data = $this->body();
            foreach ($this->userIdsWithTeacherIdsAsKeys as $teacherId=>$userId){
                $data['teacherId'] = $teacherId;
                $arrayForCreate [] = [
                    'type_id' => $this->notificationType->id,
                    'user_id' => $userId,
                    'data'    => json_encode($data),
                    'created_at' => Carbon::now()
                ];
            }
            Notification::insert($arrayForCreate);
        }
    }

//    protected function notifyToDataBase(){
//
//        $this->notifyUsersToDataBase();
//        $this->notifyUsersAsTeachersToDataBase();
//
//
//
//        return true;
//    }



    public function toFireBaseByArrayOfArraysOfUserIds(array $arrayOfArraysOfUserIds, array $notificationInfo,$title,$alarm){
        foreach ($arrayOfArraysOfUserIds as $userIds){
            $this->toFireBase($userIds,$notificationInfo,$title,$alarm);
        }
    }

    /*
     *
     * $userIds its array of user ids we want to send notification for them
     *
     *
     */
    public function toFireBase( array $userIds, array $notificationInfo,$title,$alarm){

        if(count($userIds) <= 0 )
            return 0;

        $this->toFireBaseEn($userIds,$notificationInfo);
        $this->toFireBaseAr($userIds,$notificationInfo);
        return true;

        $tokens = FirebaseToken::whereIn('user_id',$userIds)->pluck('token');

        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $alarm,
                "description" => [
                        'Alarm'=>$alarm,
                ],
                'title_loc_key' => 'hiii',
                'body_loc_key' => 'hiii_from_body',
                'sound' => 1,
 //                'image' => 'Your image link here',
            ],
            "content_available" => true,//for work in ios when send data only(without notification key)
            // "apns_priority" => 5,//for work in ios when send data only(without notification key)

            "apns"=> [
                "headers"=> [
                    "apns-push-type"> "alert"
                ]

            ],

            "data" => $notificationInfo,
        ];
        $dataString = json_encode($data);

        $headers = [
//            'Authorization: key=AAAAciCYSdc:APA91bEq8Nb4ykFoQI6_eu1At70wdak_EJHFxNWDLZTMH6MzTiPrYcsMdEPnvq4I43DWLft6sWc2nGokZuCaE98h9rUmH4_jno0NnHIJw0Tah8c8wBDq7fp_HnMHFK2-aC-Z6qjFYAiK' /*. env('FIREBASE_SERVER_API_KEY')*/,
                'Authorization: key=' . config('services.firebase.server_api_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
//        dd($response);
    }

    /*
         *
         * $userIds its array of user ids we want to send notification for them
         *
         *
         */
    public function toFireBaseEn( array $userIds, array $notificationInfo){

        if(count($userIds) <= 0 )
            return 0;

        $tokensEn = FirebaseToken::whereIn('user_id',$userIds)
            ->where('lang','en')
            ->pluck('token');

        $data = [
            "registration_ids" => $tokensEn,
            "notification" => [
                "title" => $notificationInfo['notificationTitle'],
                "body" => $notificationInfo['notificationBody'],
                "description" => [
                    'Alarm'=>$notificationInfo['notificationBody'],
                ],
                'sound' => 1,
                //                'image' => 'Your image link here',
            ],

            "data" => $notificationInfo,
        ];

        $dataString = json_encode($data);

        $headers = [
//            'Authorization: key=AAAAciCYSdc:APA91bEq8Nb4ykFoQI6_eu1At70wdak_EJHFxNWDLZTMH6MzTiPrYcsMdEPnvq4I43DWLft6sWc2nGokZuCaE98h9rUmH4_jno0NnHIJw0Tah8c8wBDq7fp_HnMHFK2-aC-Z6qjFYAiK' /*. env('FIREBASE_SERVER_API_KEY')*/,
            'Authorization: key=' . config('services.firebase.server_api_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
//        dd($response);
    }


    /*
         *
         * $userIds its array of user ids we want to send notification for them
         *
         *
         */
    public function toFireBaseAr( array $userIds, array $notificationInfo){

        if(count($userIds) <= 0 )
            return 0;

        $tokensAr = FirebaseToken::whereIn('user_id',$userIds)
            ->where('lang','ar')
            ->pluck('token');


        $data = [
            "registration_ids" => $tokensAr,
            "notification" => [
                "title" => $notificationInfo['notificationTitle_ar'],
                "body" => $notificationInfo['notificationBody_ar'],
                "description" => [
                    'Alarm'=>$notificationInfo['notificationBody_ar'],
                ],
                'sound' => 1,
                //                'image' => 'Your image link here',
            ],

            "data" => $notificationInfo,
        ];
        $dataString = json_encode($data);

        $headers = [
//            'Authorization: key=AAAAciCYSdc:APA91bEq8Nb4ykFoQI6_eu1At70wdak_EJHFxNWDLZTMH6MzTiPrYcsMdEPnvq4I43DWLft6sWc2nGokZuCaE98h9rUmH4_jno0NnHIJw0Tah8c8wBDq7fp_HnMHFK2-aC-Z6qjFYAiK' /*. env('FIREBASE_SERVER_API_KEY')*/,
            'Authorization: key=' . config('services.firebase.server_api_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
//        dd($response);
    }



    /**
     *
     * @param array-key $userIds (the key is teacher_id and the value is user_id)
     *
     *
     */
    public function toFireBaseForProccessingTeacher(array $userIds, array $notificationInfo,$title,$alarm){

        if(count($userIds) <= 0 )
            return 0;

        $this->toFireBaseForProccessingTeacherEn($userIds,$notificationInfo);
        $this->toFireBaseForProccessingTeacherAr($userIds,$notificationInfo);
        return true;

        $tokens = FirebaseToken::whereIn('user_id',$userIds)->get();

        foreach ($userIds as $teacherId=>$userId){

            $notificationInfo['teacherId'] = $teacherId;
            $teacherTokens = $tokens->where('user_id',$userId)->pluck('token');

            $data = [
                "registration_ids" => $teacherTokens,
//            "notification" => [
//                "title" => $title,
//                "description" => [
//                        'Alarm'=>$alarm,
//                ],
//                'sound' => 1,
////                'image' => 'Your image link here',
//            ],
                "data" => $notificationInfo,
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . config('services.firebase.server_api_key'),
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);


        }

    }


    /**
     *
     * @param array-key $userIds (the key is teacher_id and the value is user_id)
     *
     *
     */
    public function toFireBaseForProccessingTeacherEn(array $userIds, array $notificationInfo){

        if(count($userIds) <= 0 )
            return 0;


        $tokensEn = FirebaseToken::whereIn('user_id',$userIds)
            ->where('lang','en')
            ->get();

        foreach ($userIds as $teacherId=>$userId){

            $notificationInfo['teacherId'] = $teacherId;
            $teacherTokens = $tokensEn->where('user_id',$userId)->pluck('token');

            $data = [
                "registration_ids" => $teacherTokens,
                "notification" => [
                    "title" => $notificationInfo['notificationTitle'],
                    "body" => $notificationInfo['notificationBody'],
                    "description" => [
                            'Alarm'=>$notificationInfo['notificationBody'],
                    ],
                    'sound' => 1,
    //                'image' => 'Your image link here',
                ],
                "data" => $notificationInfo,
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . config('services.firebase.server_api_key'),
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);


        }

    }


    /**
     *
     * @param array-key $userIds (the key is teacher_id and the value is user_id)
     *
     *
     */
    public function toFireBaseForProccessingTeacherAr(array $userIds, array $notificationInfo){

        if(count($userIds) <= 0 )
            return 0;


        $tokensAr = FirebaseToken::whereIn('user_id',$userIds)
            ->where('lang','ar')
            ->get();

        foreach ($userIds as $teacherId=>$userId){

            $notificationInfo['teacherId'] = $teacherId;
            $teacherTokens = $tokensAr->where('user_id',$userId)->pluck('token');

            $data = [
                "registration_ids" => $teacherTokens,
                "notification" => [
                    "title" => $notificationInfo['notificationTitle_ar'],
                    "body" => $notificationInfo['notificationBody_ar'],
                    "description" => [
                        'Alarm'=>$notificationInfo['notificationBody_ar'],
                    ],
                    'sound' => 1,
                    //                'image' => 'Your image link here',
                ],
                "data" => $notificationInfo,
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . config('services.firebase.server_api_key'),
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);


        }

    }


}
