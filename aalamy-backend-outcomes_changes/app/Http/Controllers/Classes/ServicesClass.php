<?php


namespace App\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\Http;

class ServicesClass
{

//    public static $baseStoredFilePath = "storage/";

//    public static function GetVideoMimesType(){
//        return [
//          'video/x-flv',
//          'application/x-mpegURL',
//          'video/mp4',
//          'video/MP2T',
//          'video/3gpp',
//          'video/x-msvideo',
//          'video/x-ms-wmv',
//        ];
//    }

//    public static function storeFiles($media,$folderName,$mediaName='default'){
//
//        $folderName = str_replace(' ','-',$folderName)/*Str::slug($folderName, '-')*/;
//
//        $folderName = $folderName.'/'.date('Y-m-d');
//        $mediaName = $mediaName.'-'.Carbon::now()->microsecond ;
//        $mediaName = Str::slug($mediaName, '-'). '.' . $media->extension();
//        $media->storeAs($folderName, $mediaName);
//        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$folderName;
//        $image = $path . '/' . $mediaName;
//        return $image;
//    }

    public static function ExchangeToUSD($value,$currency){

        try {
            $response_json = Http::get('https://api.exchangerate.host/convert?from='
                .$currency.'&to=USD&amount='.$value);
            $arrayResponse = json_decode($response_json,true);

            if($arrayResponse['success']){
                return $arrayResponse['result'];
            }else{
                return false;
            }
        }catch(\Exception $e) {
            return false;
        }

    }


    public static function generateRandomString($length = 10,$characters ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*_-') {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function getNiceNamesTowDimensionArray($requestArray,Array $filedNames,$input){
        $niceNames = [];
        foreach ((array) $requestArray as $key => $element){
            foreach ($filedNames as $filedName){
                $niceNames[$input.'.'.$key.'.'.$filedName] =
                    trans('validationParameters.'.$filedName).'['.($key+1).']';
            }
        }
        return $niceNames;
    }

    public static function getNiceNamesOneDimensionArray($requestArray,$input){
        $niceNames = [];
        foreach ((array) $requestArray as $key => $element){
                $niceNames[$input.'.'.$key] =
                    trans('validationParameters.'.$input).'['.($key+1).']';
        }
        return $niceNames;
    }

//
//    public static function DeleteMoreThanFile(array $paths){
//        foreach ($paths as $path)
//            Self::DeleteFile($path);
//    }
//
//    public static function DeleteFile($fullPath){
//        $oldPath = Self::getFilePathInStorage($fullPath);
//        if(File::exists(storage_path($oldPath)))
//            File::delete(storage_path($oldPath));
//    }



//    public static function getFilePathInStorage($fullPath){
//        $arrayPathAsWords = explode('/',$fullPath);
//        $path = '';
//        $storageKeyInArray = array_search("storage",$arrayPathAsWords);
//        foreach ($arrayPathAsWords as $key=>$wordFromPath){
//            if($key>$storageKeyInArray){
//                $path = $path.'/'.$wordFromPath;
//            }
//        }
//
//        return $path;
//    }

//    /**
//     * store the base64 file and return the path in file storage
//     * @var string $base64_file
//     * @var string $folderName
//     * @var string $mediaName
//     * @return string
//     */
//    public static function storeBase64File($base64_file,$folderName,$mediaName='default'){
//
//        $folderName = str_replace(' ','-',$folderName);
//        $folderName = $folderName.'/'.date('Y-m-d');
//
//        if(!str_contains($base64_file,';'))
//            throw new ErrorMsgException('invalid file format');
//
//        list($type, $file_string) = explode(';', $base64_file);
//
//        //explode the type string to get extension from it
//        $typeElements = explode('/', $type);
//
//        //extension will be the last item in the array
//        $fileExtension = $typeElements[count($typeElements)-1];
//
//        $mediaName = $mediaName.'-'.Carbon::now()->microsecond . '.' . $fileExtension;
//
//        list(, $fileEncoded) = explode(',', $file_string);
//        Storage::disk('local')->put($folderName.'/'.$mediaName, base64_decode($fileEncoded));
////        $path = config('panel.default_store_path_inside_default_disk').'/'.$folderName.'/'.$mediaName;
//        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$folderName.'/'.$mediaName;
//
//        return $path;
//    }

//    public static function getExtensionFileFromName($name){
//        $explodedName = explode('.',$name);
//        return $explodedName[count($explodedName)-1];
//    }

    public static function checkCodeExpireDate($codeCreatedAt,$codeLongTime,$transMsg){
        $expireDate = Carbon::createFromFormat(
            'Y-m-d'.' '.'H:i:s',$codeCreatedAt
        )->addMinutes($codeLongTime);
        if($expireDate < Carbon::now() )
            throw new ErrorMsgException($transMsg);

    }

    public static function dispatchJob($object){
        dispatch_now($object);

    }


    public static function toTimezone($value,$fromTimezone,$toTimezone=null){
        $toTimezone = is_null($toTimezone)? 'Utc':$toTimezone;
       return (new Carbon($value,$fromTimezone))
            ->setTimezone(new CarbonTimeZone($toTimezone));
    }

    /*public static function removeWithoutDeletedItemsScopeFromAllModels($soft_delete,User $user){
        if(is_null($soft_delete) || !UserServices::isSuperAdmin($user) )
            return true;

        foreach (ApplicationModules::getConstants() as $key=>$moduleName){
            $models = File::files(app_path('Modules/'.$moduleName.'/Models'));
            foreach ($models as $model){
                $fileName = $model->getRelativePathName();
                list($fileName,$extension) = explode('.',$fileName);
                $classpath = 'Modules\\'.$moduleName.'\Models\\'.$fileName;

                try {
                    $classpath::removeWithoutDeletedItemsScope();
                }catch (\BadMethodCallException $e){

                }
            }

        }
    }*/


    public static function getAllModelsPaths(){
        $modelsPaths = [];
        foreach (ApplicationModules::getConstants() as $moduleName) {
            $path = app_path() .'/Modules/'.$moduleName. "/Models";

            $results = scandir($path);

            foreach ($results as $result) {
                if ($result === '.' or $result === '..') continue;
                $filename = $path . '/' . $result;
//                if (is_dir($filename)) {
//                    $modelsPaths = array_merge($modelsPaths, getModels($filename));
//                } else {
                $pathInsideModule = explode('app/',$filename)[1];
                $pathWithoutExtinsion = substr($pathInsideModule, 0, -4);
                $pathWithoutExtinsion = str_replace('/','\\',$pathWithoutExtinsion);
                $modelsPaths[] = $pathWithoutExtinsion;
//                }
            }
        }
        return $modelsPaths;
    }


}
