<?php


namespace Modules\HelpCenter\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Modules\HelpCenter\Models\HelpCenterUserGuide;
use Modules\HelpCenter\Models\HelpCenterUserGuideImage;
use Modules\HelpCenter\Models\HelpCenterUserGuideVideo;

class HelpCenterServices
{

    public static function checkOrderByParameters(string $orderBy,string $orderType,Array $fields){
        $fields = array_merge($fields,['created_at']);
        Self::checkOrderByFiled($orderBy,$fields);
        Self::checkOrderTypeFiled($orderType);
    }

    public static function checkOrderByFiled(string $orderBy,Array $fields){
        if(!in_array($orderBy,$fields))
            throw new ErrorMsgException(transMsg('invalid_order_by_field',ApplicationModules::HELP_CENTER_MODULE_NAME));
    }

    public static function checkOrderTypeFiled(string $orderType){
        if(!in_array($orderType,config('HelpCenter.panel.order_by_types')))
            throw new ErrorMsgException(transMsg('invalid_order_by_field',ApplicationModules::HELP_CENTER_MODULE_NAME));
    }

    public static function deleteHelpCenter(HelpCenterUserGuide $helpCenter){
        $helpCenterPictureLinks =  HelpCenterUserGuideImage::where('user_guide_id',$helpCenter->id)
            ->pluck('image')->toArray();

        $helpCenterVideoLinks =  HelpCenterUserGuideVideo::where('user_guide_id',$helpCenter->id)
            ->pluck('video')->toArray();
        FileManagmentServicesClass::deleteFiles(array_merge($helpCenterPictureLinks,$helpCenterVideoLinks));
//        ServicesClass::DeleteMoreThanFile(array_merge($helpCenterPictureLinks,$helpCenterVideoLinks));
        $helpCenter->delete();
    }

}
