<?php

namespace Modules\User\Http\Controllers\Classes\ImportStudentClasses;

use App\Exceptions\ErrorMsgException;

class FileServices
{

    /**
     * supported file to import student
     */
    protected static $fileTypes = [
        'excel' => [
          'educator' => EducatorStudentExcelFile::class,
          'school' => SchoolStudentExcelFile::class,
        ]
//        ,ExcelFile::class,
    ];
    const FILE_FIELD_NAME_IN_REQUEST = 'file';

    /**
     * name of file uploaded in the request
     * ex: $request->file,$request->File,..
     */
//    protected static $fileFieldNameInRequest = 'file';

    /**
     * @return array-key
     */
    public static function getValidFileTypes():array
    {
        return self::$fileTypes;
    }

    public static function getFileFieldName(){
        return self::FILE_FIELD_NAME_IN_REQUEST;
    }

    /**
     * @param string $fileType
     * @return FileInterface depends on file type(excel,...)
     * @throws ErrorMsgException
     */
    public static function createFileClassByType($fileType,$for):FileInterface
    {
        if(!in_array($for,['educator','school']))
            throw new ErrorMsgException('invalid createFileClassByType parameter');
        Self::checkValidFileType($fileType);
        return new self::$fileTypes[$fileType][$for];
    }

    /**
     * @param string $fileType
     * check if the type is supported to import from
     * @throws ErrorMsgException
     */
    public static function checkValidFileType($fileType){
        if(!key_exists($fileType,Self::getValidFileTypes()))
            throw new ErrorMsgException('invalid file type');
    }

}
