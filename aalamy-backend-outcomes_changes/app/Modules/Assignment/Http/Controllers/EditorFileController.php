<?php

namespace Modules\Assignment\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditorFileController extends Controller
{

    public function storeFile(Request $request){
    ini_set('max_execution_time', '0');
    // ini_set('memory_limit', '100M');
        $validator = Validator::make($request->all(),[
            'source' => 'required'
        ]);
        if($validator->failed()){
            throw new ErrorMsgException('source is required');
        }
        if(strtolower($request->source_type) == 'base64'){
            $path = FileManagmentServicesClass::storeBase64File($request->source,'editor-files/generated-from-base');
        }else{
            $path = FileManagmentServicesClass::storeFiles($request->source,'editor-files/generated-from-files');
        }

        return ApiResponseClass::successResponse([
            'path' => baseRoute().$path
        ]);

    }


}
