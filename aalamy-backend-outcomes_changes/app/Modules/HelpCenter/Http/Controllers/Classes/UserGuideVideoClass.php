<?php


namespace Modules\HelpCenter\Http\Controllers\Classes;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Modules\HelpCenter\Models\HelpCenterUserGuide;
use Modules\HelpCenter\Models\HelpCenterUserGuideVideo;

class UserGuideVideoClass
{


    public function addMoreThanVideoToUserGuide(HelpCenterUserGuide $userGuide,?array $videos){
        if(isset($videos)){
            foreach ($videos as $video){
                $this->addVideoToUserGuide($userGuide,$video);
            }
        }
    }


    public function addVideoToUserGuide(HelpCenterUserGuide $userGuide, $video){
        $path = FileManagmentServicesClass::storeFiles($video,"help_center/user_guide/{$userGuide->id}/videos");
//        $path = ServicesClass::storeFiles($video,"help_center/user_guide/{$userGuide->id}/videos");
        return HelpCenterUserGuideVideo::create([
            'user_guide_id' => $userGuide->id,
            'video' => $path,
        ]);
    }


    public function deleteMoreThanVideoFromUserGuide(?array $videoIds){
        if(isset($videoIds)){
            foreach ($videoIds as $videoId){
                $this->deleteVideoFromUserGuide($videoId);
            }
        }
    }

    public function deleteVideoFromUserGuide($videoId){
        return HelpCenterUserGuideVideo::find($videoId)->delete();
    }


}
