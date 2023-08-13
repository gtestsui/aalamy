<?php
/**
 * Created by PhpStorm.
 * User: Abd Shammout
 * Date: 09/12/2021
 * Time: 11:21 PM
 */

namespace Modules\Meeting\Http\Controllers\BBB;


use BigBlueButton\Parameters\GetRecordingsParameters;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class MeetRecodeBBB
{

    private static
        $PROCESSING     = 'processing',
        $PROCESSED      = 'processed',
        $PUBLISHED      = 'published',
        $UNPUBLISHED    = 'unpublished',
        $DELETED        = 'deleted';


    private static function getRecording($status = null, $meetingId = null, $recordId = null){
        $recordingsParameters = new GetRecordingsParameters();
        if ($status)
            $recordingsParameters->setState($status);
        if ($meetingId)
            $recordingsParameters->setMeetingId($meetingId);
        if ($recordId)
            $recordingsParameters->setRecordId($recordId);
        return Bigbluebutton::getRecordings($recordingsParameters);
    }


    public static function all(){
        return self::getRecording();
    }


    public static function deleteRecordings($recordID){
        if (!is_array($recordID)){
            $recordID = [$recordID];
        }
        return Bigbluebutton::deleteRecordings([
            'recordID' => $recordID
        ]);
    }


    public static function getRecordingByMeetingId($meetingId){
        return self::getRecording(null, $meetingId, null);
    }


    public static function getRecordingByRecordId($recordId){
        return self::getRecording(null, null, $recordId);
    }

    public static function getRecordingProcessing(){
        return self::getRecording(self::$PROCESSING);
    }

    public static function getRecordingProcessed(){
        return self::getRecording(self::$PROCESSED);
    }

    public static function getRecordingPublished(){
        return self::getRecording(self::$PUBLISHED);
    }

    public static function getRecordingUnpublished(){
        return self::getRecording(self::$UNPUBLISHED);
    }

    public static function getRecordingDeleted(){
        return self::getRecording(self::$DELETED);
    }

}
