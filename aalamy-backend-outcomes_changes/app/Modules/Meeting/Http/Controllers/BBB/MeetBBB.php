<?php
/**
 * Created by PhpStorm.
 * User: Abd Shammout
 * Date: 16/11/2021
 * Time: 11:10 AM
 */

namespace Modules\Meeting\Http\Controllers\BBB;



use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Http\Response;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class MeetBBB
{
    private static $PASSWORD_MODERATOR = 'moderator';
    private static $PASSWORD_ATTENDEE = 'attendee';

    /**
     * @note this code added by joybox developer team
     * @var string
     */
    private static $DURATION = 0;


    public static function setModeratorPassword($password){
        self::$PASSWORD_MODERATOR = $password;
    }

    public static function setAttendeePassword($password){
        self::$PASSWORD_ATTENDEE = $password;
    }

    /**
     * @note this code added by joybox developer team
     * @param $password
     */
    public static function setDuration($duration){
        self::$DURATION = $duration;
    }


    /**
     * @return array
     */
    public static function allMeetings(){
       return Bigbluebutton::all();
    }

    public static function start($meetingId, $sessionTitle, $maxParticipants, $usernameModerator, $userID){
        return Bigbluebutton::start([
            'meetingID' => $meetingId,
            'attendeePW' => self::$PASSWORD_ATTENDEE, //attendee password here
            'moderatorPW' => self::$PASSWORD_MODERATOR, //moderator password set here
            'meetingName' => $sessionTitle,//for join meeting
            'userName' => $usernameModerator,//for join meeting
            'userID' => $userID,//id for join meeting
            'record' => true,//for record meeting
            'maxParticipants' => $maxParticipants,//for number person in meeting
//            'duration' => config('constants.api_config.duration_meet'),//duration for meeting
            'duration' => self::$DURATION,//duration for meeting
            'logoutUrl' => env('APP_FRONT_URL'),//for redirect after finish meeting
            //'redirect' => false // only want to create and meeting and get join url then use this parameter
        ]);
    }


    /**
     * @param $meetingId
     * @param $sessionTitle
     * @param $maxParticipants
     * @return bool
     */
    public static function create($meetingId, $sessionTitle, $maxParticipants){
        $meeting = new CreateMeetingParameters($meetingId, $sessionTitle);
        $meeting->setAttendeePassword(self::$PASSWORD_ATTENDEE)
            ->setModeratorPassword(self::$PASSWORD_MODERATOR)
            ->setMaxParticipants($maxParticipants)
            ->setLogoutUrl(config('panel.app_front_url'))
            ->setRecord(true)
//            ->setDuration(config('constants.api_config.duration_meet'));
            ->setDuration(self::$DURATION);

        return Bigbluebutton::create($meeting);
    }



    /**
     * @param $meetingId
     * @param $sessionTitle
     * @param $maxParticipants
     * @param $usernameModerator
     * @param $userID
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function createAndStart($meetingId, $sessionTitle, $maxParticipants, $usernameModerator, $userID){
        $url = Bigbluebutton::start([
            'meetingID' => $meetingId,
            'attendeePW' => self::$PASSWORD_ATTENDEE, //attendee password here
            'moderatorPW' => self::$PASSWORD_MODERATOR, //moderator password set here
            'meetingName' => $sessionTitle,//for join meeting
            'userName' => $usernameModerator,//for join meeting
            'userID' => $userID,//id for join meeting
            'record' => true,//for record meeting
            'maxParticipants' => $maxParticipants,//for number person in meeting
//            'duration' => config('constants.api_config.duration_meet'),//duration for meeting
            'duration' => self::$DURATION,//duration for meeting
            'logoutUrl' => env('APP_FRONT_URL'),//for redirect after finish meeting
            //'redirect' => false // only want to create and meeting and get join url then use this parameter
        ]);
        return $url;

        return redirect()->to($url);
    }


    /**
     * @param $meetingId
     * @param $username
     * @param $userId
     * @param $password
     * @return mixed
     */
    private static function join($meetingId, $username, $userId, $password){
        $join = new JoinMeetingParameters($meetingId, $username, $password);
        $join->setUserId($userId);
        $join->setRedirect(true);
//        $join->setCustomParameter('guest', 'false');
        return Bigbluebutton::join($join);
    }


    /**
     * @param $meetingId
     * @param $username
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public static function joinAttendee($meetingId, $username, $userId){
        if (!self::isRun($meetingId))
            throw new \Exception("Teacher Not Started Meeting", Response::HTTP_BAD_REQUEST);
        return self::join($meetingId, $username, $userId, self::$PASSWORD_ATTENDEE);
    }


    /**
     * @param $meetingId
     * @param $username
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public static function joinModerator($meetingId, $username, $userId){
        if (!self::isRun($meetingId))
            throw new \Exception("can't create Meeting", Response::HTTP_BAD_REQUEST);
        return self::join($meetingId, $username, $userId, self::$PASSWORD_MODERATOR);
    }

//    /**
//     * join as moderator or attendee with customized error msg
//     * @param $meetingId
//     * @param $username
//     * @param $userId
//     * @return mixed
//     * @throws \Exception
//     */
//    public static function joinAsModeratorOrAttendee($meetingId, $username, $userId, $password){
//        if (!self::isRun($meetingId))
//            throw new \Exception("The Meeting has been stopped", Response::HTTP_BAD_REQUEST);
//        return self::join($meetingId, $username, $userId, $password);
//    }


    /**
     * @param $meetingId
     * @return mixed
     */
    public static function isRun($meetingId){
        return Bigbluebutton::isMeetingRunning([
            'meetingID' => $meetingId,
        ]);
    }


    /**
     * @param $meetingId
     * @return mixed
     */
    public static function info($meetingId){
        return Bigbluebutton::getMeetingInfo([
            'meetingID' => $meetingId,
            'moderatorPW' => self::$PASSWORD_MODERATOR
        ]);
    }


    /**
     * @param $meetingId
     * @return mixed
     */
    public static function end($meetingId){
        return Bigbluebutton::close([
            'meetingID' => $meetingId,
            'moderatorPW' => self::$PASSWORD_MODERATOR
        ]);
    }

}
