<?php


namespace Modules\HelpCenter\Http\Controllers\Classes;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Modules\HelpCenter\Models\HelpCenterUserGuide;
use Modules\HelpCenter\Models\HelpCenterUserGuideImage;
use Modules\HelpCenter\Models\HelpCenterUserGuideVideo;

class UserGuideImageClass
{


    public function addMoreThanImageToUserGuide(HelpCenterUserGuide $userGuide,?array $images){
        if(isset($images)){
            foreach ($images as $image){
                $this->addImageToUserGuide($userGuide,$image);
            }
        }
    }

    public function addImageToUserGuide(HelpCenterUserGuide $userGuide, $image){
        $path = FileManagmentServicesClass::storeFiles($image,"help_center/user_guide/{$userGuide->id}/images");
//        $path = ServicesClass::storeFiles($image,"help_center/user_guide/{$userGuide->id}/images");
        return HelpCenterUserGuideImage::create([
            'user_guide_id' => $userGuide->id,
            'image' => $path,
        ]);
    }

    public function deleteMoreThanImageFromUserGuide(?array $imageIds){
        if(isset($imageIds)){
            foreach ($imageIds as $imageId){
                $this->deleteImageFromUserGuide($imageId);
            }
        }
    }

    public function deleteImageFromUserGuide($imageId){
        /*return */HelpCenterUserGuideImage::find($imageId)->delete();
    }


}
