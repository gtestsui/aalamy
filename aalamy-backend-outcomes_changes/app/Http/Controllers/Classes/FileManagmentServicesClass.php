<?php


namespace App\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagmentServicesClass{


    public static function GetVideoMimesType(){
        return [
            'video/x-flv',
            'application/x-mpegURL',
            'video/mp4',
            'video/MP2T',
            'video/3gpp',
            'video/x-msvideo',
            'video/x-ms-wmv',
        ];
    }


    /**
     * @note store files from request
     */
    public static function storeFiles($media,$folderName,$mediaName='default'){
        $mediaName = pathinfo($media->getClientOriginalName(), PATHINFO_FILENAME);

        $folderName = str_replace(' ','-',$folderName)/*Str::slug($folderName, '-')*/;

        $folderName = $folderName.'/'.date('Y-m-d');
        $mediaName = $mediaName.'-'.Carbon::now()->microsecond ;
        $mediaName = Str::slug($mediaName, '-'). '.' . $media->getClientOriginalExtension();
        $media->storeAs($folderName, $mediaName);
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$folderName;
        $image = $path . '/' . $mediaName;
        return $image;
    }

    /**
     * use this function when you want to store files in defined path
     * @note store files from request
     */
    public static function storeFilesInStaticPath($media,$folderName,$mediaName,$mediaExtension){

        $mediaName = $mediaName.'.'.$mediaExtension;
        $media->storeAs($folderName, $mediaName);
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$folderName;
        $image = $path . '/' . $mediaName;
        return $image;
    }


    /**
     * @param array|string $paths
     */
    public static function deleteFiles($paths){
        if(!is_array($paths))
            $paths = [$paths];
        $fullPathsInDisk = [];
        foreach ($paths as $path){
            $diskRoot = FileSystemServicesClass::getDiskRoot();
            $fullPathInsideDefaultDisk = $diskRoot.Self::getFilePathInDefaultStoragePathInsideDisk($path);
            array_push(
                $fullPathsInDisk,
                $fullPathInsideDefaultDisk
            );

        }
        File::delete($fullPathsInDisk);
    }

    /**
     * @param string $fullPath
     * @return string
     */
    public static function getFilePathInDefaultStoragePathInsideDisk($fullPath){
        $defaultFolder = FileSystemServicesClass::getDefaultStoragePathInsideDisk();
        //return the path start after disk path and default storage path
        $arrayPath = explode($defaultFolder,$fullPath);
        return end($arrayPath);
    }


    /**
     * store the base64 file and return the path in file storage
     * @var string $base64_file
     * @var string $folderName
     * @var string $mediaName
     * @return string
     */
    public static function storeBase64File($base64_file,$folderName,$mediaName='default'){

        $folderName = str_replace(' ','-',$folderName);
        $folderName = $folderName.'/'.date('Y-m-d');

        if(!str_contains($base64_file,';'))
            throw new ErrorMsgException('invalid file format');

//        list($type, $file_string) = explode(';', $base64_file);
        $explodedBase64 = explode(';', $base64_file);
        $type = $explodedBase64[0];
        $file_string = $explodedBase64[count($explodedBase64)-1];
        //explode the type string to get extension from it
        $typeElements = explode('/', $type);

        //extension will be the last item in the array
        $fileExtension = $typeElements[count($typeElements)-1];

        $mediaName = $mediaName.'-'.Carbon::now()->microsecond . '.' . $fileExtension;

        list(, $fileEncoded) = explode(',', $file_string);
        Storage::disk(FileSystemServicesClass::getDiskName())->put($folderName.'/'.$mediaName, base64_decode($fileEncoded));
//        $path = config('panel.default_store_path_inside_default_disk').'/'.$folderName.'/'.$mediaName;
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$folderName.'/'.$mediaName;

        return $path;
    }


    public static function getExtensionFileFromName($name){
        $explodedName = explode('.',$name);
        return $explodedName[count($explodedName)-1];
    }

    public static function resolveLinkFromDataToDownload(Model $object,$columnName){
        return FileSystemServicesClass::getDiskBaseRoot().$object->getRawOriginal($columnName);

    }

    public static function resolveLinkFromDefaultStorageToDownload($path){
        return FileSystemServicesClass::getDiskBaseRoot().$path;

    }

    public static function getFullPath($innerPath){
        return baseRoute().$innerPath;
    }


}
