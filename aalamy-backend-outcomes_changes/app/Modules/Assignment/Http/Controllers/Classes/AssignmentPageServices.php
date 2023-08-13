<?php

namespace Modules\Assignment\Http\Controllers\Classes;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Carbon\Carbon;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class AssignmentPageServices{

//    public $s='var s';
//
//    public static function QuestionTypes(){
//        return new self();
//    }

    private const ASSIGNMENT_PAGES_PATH = 'assignment-pages';

    public static function addPagesToAssignment(array $pages,Assignment $assignment){

        foreach ($pages as $key=>$page){
            self::addPageAsLinkToAssignment($page,$assignment,$key);
        }
    }

    public static function addPageAsLinkToAssignment($page,Assignment $assignment,$key='0'){
        $pathInsideDefaultStoragePath = FileManagmentServicesClass::getFilePathInDefaultStoragePathInsideDisk($page);
        $pathInsideDefaultStoragePath = substr($pathInsideDefaultStoragePath, 1);
        $originalExtinsion = FileManagmentServicesClass::getExtensionFileFromName($pathInsideDefaultStoragePath);

        $assignmentFolderName = str_replace(' ','-',$assignment->name);
        $toFolderPath = FileSystemServicesClass::getDiskRoot().'/'.self::ASSIGNMENT_PAGES_PATH.'/'.$assignmentFolderName;
        if(!File::exists($toFolderPath)) {
            File::makeDirectory($toFolderPath);
        }
        $newMediaName = $key.'-'.Carbon::now()->microsecond . '.' . $originalExtinsion;
        $newPathInsideDefaultStoragePath = self::ASSIGNMENT_PAGES_PATH."/$assignmentFolderName/$newMediaName";



        Storage::disk(FileSystemServicesClass::getDiskName())
            ->copy($pathInsideDefaultStoragePath, $newPathInsideDefaultStoragePath);
        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$newPathInsideDefaultStoragePath;
        return Page::create([
            'assignment_id' => $assignment->id,
            'page' => $path,
            'is_locked' => config('Assignment.panel.assignment_page.is_locked_default'),
            'is_hidden' => config('Assignment.panel.assignment_page.is_hidden_default'),
        ]);
    }

    public static function addPageAsBase64ToAssignment($page,Assignment $assignment,$key='0'){
        $path = FileManagmentServicesClass::storeBase64File($page,self::ASSIGNMENT_PAGES_PATH.'/'.$assignment->name,$key);
        return Page::create([
            'assignment_id' => $assignment->id,
            'page' => $path,

        ]);
    }


}
