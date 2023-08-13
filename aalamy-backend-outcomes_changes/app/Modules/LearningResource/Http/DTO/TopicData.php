<?php


namespace Modules\LearningResource\Http\DTO;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Models\Topic;

final class TopicData extends ObjectData
{
    public ?int      $id=null;
    public int       $user_id;
    public ?int      $topic_id;
    public ?int      $school_id;
    public ?int      $educator_id;
    public ?int      $teacher_id;
    public string    $name;
    public string    $read_share_type;
    public ?string    $write_share_type;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,?Topic $topic=null): self
    {
        $user = $request->user();

        //if the user choose topic then make inner-topic owners as same the chosen topic
        list($schoolId,$teacherId,$educatorId) = LearningResourceServices::prepareLearningResourceOwner(
            $user,$request,$topic
        );

        //we have check here because the types its dynamic depend on topic
        LearningResourceServices::checkValidShareTypeWithMyAccount(
            $schoolId,$teacherId,$educatorId,$request->read_share_type
        );
        if(!is_null($request->write_share_type)){
            //we have check here because the types its dynamic depend on topic
            LearningResourceServices::checkValidShareTypeWithMyAccount(
                $schoolId,$teacherId,$educatorId,$request->write_share_type
            );
        }

        return new self([
            'user_id'    => isset($topic)?(int)$topic->user_id:$user->id,
            'topic_id'    => isset($request->topic_id)?(int)$request->topic_id:null,
            'school_id'   => isset($schoolId)?(int)$schoolId:null,
            'teacher_id'   => isset($teacherId)?(int)$teacherId:null,
            'educator_id' => isset($educatorId)?(int)$educatorId:null,
            'name'        => $request->name,
            'read_share_type'   => $request->read_share_type,
            //if the user is school or teacher then he can customize write_share_type
            //else will be private always
            'write_share_type'  => isset($request->write_share_type)?$request->write_share_type:configFromModule('panel.learning_resource_read_share_types.private',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME)
        ]);
    }




}
