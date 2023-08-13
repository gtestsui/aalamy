<?php


namespace App\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class FileSystemServicesClass{


    public static function getDiskName(){
        $diskName = config('filesystems.default');
        if(is_null($diskName))
            return 'local';
        return $diskName;
    }

    //ex:public_path('/') or storage_path('/')
    public static function getDiskBaseRoot(){
        $diskName = Self::getDiskName();
        $diskRoot = config('filesystems.disks.'.$diskName.'.base_root');
        if(is_null($diskRoot))
            return  public_path('/');
        return $diskRoot;
    }

    //ex:public_path('/storage...') or storage_path('/adasd...')
    public static function getDiskRoot(){
        $diskName = Self::getDiskName();
        $diskRoot = config('filesystems.disks.'.$diskName.'.root');
        return $diskRoot;
    }

    //ex:the folder name inside public_html('/name')
    public static function getDefaultStoragePathInsideDisk(){
        $diskName = Self::getDiskName();
        $defaultPathInsideDisk = config('filesystems.disks.'.$diskName.'.default_storage_path','storage');

        return $defaultPathInsideDisk;
    }

}
