<?php


use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Mark\Models\MongoSession;
use Modules\RosterAssignment\Models\RosterAssignment;

if(!function_exists('transText')){
    /**
     * @var string $translateString the string we want to translate it
     * @var string $moduleName is same module name in the structure
     * @var array $parameters is the parameter you want to send to trans msg
     * (start with upper case letter and the rest chars lower case)
     * @return string
     */
    function transMsg($translateString ,$moduleName = null,array $parameters=[]){
        if(is_null($moduleName))
            return trans('messages.'.$translateString,$parameters);
        else
            return trans("{$moduleName}::messages.".$translateString,$parameters);

    }
}



if(!function_exists('configFromModule')){
    /**
     * @var string $string the string we want to get it from config
     * @var string $moduleName is same module name in the structure
     * @var mixed|null $default is the default value if the real value doesn't found
     * @return mixed depends on the value you are trying to access it in config file
     */
    function configFromModule($string ,$moduleName,$default=null){
        return config("{$moduleName}.{$string}",$default);
    }

}


if(!function_exists('getModuleIdentify')){

    function getModuleIdentify(string $moduleName){
        return configFromModule('panel.modules.'.$moduleName,
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);
    }

}

if(!function_exists('getModuleOwnerType')){

    function getModuleOwnerType(string $owner){
        return configFromModule('panel.module_types.'.$owner,
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);
    }

}

if(!function_exists('getModuleUsageType')){

    function getModuleUsageType(string $type){
        return configFromModule('panel.modules_usage_types.'.$type,
            ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME);
    }

}


if(!function_exists('transValidationParameter')){
    function transValidationParameter($parameterName ,$moduleName = null){
        if(is_null($moduleName))
            return trans('validationParameters.'.$parameterName);
        else
            return trans("{$moduleName}::validationParameters.".$parameterName);

    }
}

if(!function_exists('transRuleMsg')){
    function transRuleMsg($translateString ,$moduleName = null){
        if(is_null($moduleName))
            return trans('validationRuleMessages.'.$translateString);
        else
            return trans("{$moduleName}::validationRuleMessages.".$translateString);

    }
}

if(!function_exists('baseRoute')){
    function baseRoute(): string
    {
        // return url('/').'/'.'/classKick/';
       return url('/').'/Alaamy/public/';
    }
}

/**
 * return default profile picture path depends on user account type
 */
if(!function_exists('defaultUserImage')){
    function defaultUserImage($userType): string
    {
//        if($userType == 'educator')
//            return  baseRoute().'storage/default_user_images/educator-male.png';
//        if($userType == 'student')
//            return  baseRoute().'storage/default_user_images/student-male.png';
//        if($userType == 'schoolBuilding')
//            return  baseRoute().'storage/default_user_images/school-building.png';
//        if($userType == 'school')
//            return  baseRoute().'storage/default_user_images/school-manager.png';

//        return baseRoute()."storage/default_profile_picture/{$userType}.png";
        return  baseRoute().'storage/default_user_images/student-male.png';
        return 'Path for default image ';
    }
}

if(!function_exists('subStrTitle')){
    function subStrTitle($title): string
    {
        if(strlen($title) > 20)
            return substr($title,0,20).'...';
        return $title;

    }
}


if(!function_exists('subStrDescription')){
    function subStrDescription($description): string
    {
        if(strlen($description) > 70)
            return substr($description,0,70).'...';
        return $description;

    }
}


if(!function_exists('getFullNameSeperatedByDash')){
    function getFullNameSeperatedByDash(string $fname , string $lname) : string
    {
        return $fname.'-'.$lname;
    }
}



if(!function_exists('changeLang')){
    function changeLang(string $lang='en') : void
    {
        App::setLocale($lang);
    }
}

if(!function_exists('dispatchJob')){
    function dispatchJob($job) : void
    {
        dispatch_now($job);
    }
}


if(!function_exists('refactorCreatedAtFormat')){
    function refactorCreatedAtFormat($createdAt,$withTime=true)
    {
        if(is_null($createdAt))
            return null;
        if($withTime){
            return Carbon::createFromFormat(
                'Y-m-d'.' '.'H:i:s',$createdAt
            )->format('Y-m-d'.' '.'H:i:s');
        }else{
            return (new Carbon($createdAt))
                ->format(config('panel.date_format'));
        }
    }
}


if(!function_exists('getUserTokenFromRequest')){
    function getUserTokenFromRequest(Request $request)
    {
        return substr(($request->headers->get('Authorization')),7);
    }
}



if(!function_exists('calculateRosterAssignmentFullMark')){
    /**
     * @param RosterAssignment $rosterAssignment
     * @return float|int
     */
    function calculateRosterAssignmentFullMark($rosterAssignment){
        $fullMark = 0;
        $questionInRosterAssignments = MongoSession::query()
            ->where('component', 'like', '%WithOptions%')
            ->where(function ($query) use ($rosterAssignment) {
                return $query->where('roster_assignment', (string)$rosterAssignment->id)
                    ->orWhere('assignment', (string)$rosterAssignment->assignment_id);
            })
            ->get();
        foreach ($questionInRosterAssignments as $question){
            $fullMark += round($question['body']['mark'],2);
        }
        return $fullMark;
    }

}


if(!function_exists('deleteFromArray')){
    function deleteFromArray(array &$array,$value)
    {
        $newArray = [];
        $found = false;
        foreach ($array as $item){
            if(!$found && $item == $value)
                $found = true;
            else
                $newArray[] = $item;
        }
        $array = $newArray;

//        $key = array_search($value, $array);
//        if (($key) !== false)
//        {
//            #deleting the key found
//            unset($array[$key]);
//        }

    }
}


/**
 * this method just for debug
 */
if(!function_exists('throwError')){
    function throwError($object): string
    {
        throw new \App\Exceptions\ErrorMsgException(json_encode($object));
    }
}

