<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner\DiscussionCornerByOwnerManagementFactory;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner\ManageDiscussionCorner;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerPostFile;
use Modules\DiscussionCorner\Models\DiscussionCornerPostPicture;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class DiscussionCornerServices
{

    /**
     *
     */
    public static function initializeManageDiscussionClass($educatorId,$schoolId):ManageDiscussionCorner
    {

        //the owner of the discussion corner we trying to add in it
        if(!is_null($educatorId)){
            $postOn = 'educator';
            $postOwnerObject = Educator::findOrFail($educatorId);
        }else{
            $postOn = 'school';
            $postOwnerObject = School::findOrFail($schoolId);

        }
//        return Self::createManageDiscussionClassByCornerOwner($postOn,$postOwnerObject);
        return DiscussionCornerByOwnerManagementFactory::create($postOn,$postOwnerObject);
    }


    public static function deletePost(DiscussionCornerPost $post){
        $postPictureLinks =  DiscussionCornerPostPicture::where('post_id',$post->id)
            ->pluck('picture')->toArray();

        $postFileLinks =  DiscussionCornerPostFile::where('post_id',$post->id)
            ->pluck('file')->toArray();
        FileManagmentServicesClass::deleteFiles(array_merge($postFileLinks,$postPictureLinks));
//        ServicesClass::DeleteMoreThanFile(array_merge($postFileLinks,$postPictureLinks));
        $post->delete();
    }

    public static function deleteSurvey(DiscussionCornerSurvey $survey){
        $survey->delete();
    }



}
