<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByFileType;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\Page;


class AssignmentLearningResourcePdf implements GeneratedAssignmentTypeInterface
{

    private  $folderName;
    private  Assignment $assignment;
    public function __construct(Assignment $assignment){
        $this->folderName = 'learning-resource/generated-assignment'.'/'.date('Y-m-d');
        $this->assignment = $assignment;

    }

    public function generate(?array $pageIds=null){

        $pages = $this->getTargetPages($this->assignment,$pageIds);

        $pdf = PDF::loadView('LearningResource::assignment-pages', [
            'pages' => $pages
        ]);

        return $this->storeInSystem($pdf->output());

//        return $pdf->download('onlinewebtutorblog.pdf');
//        return $pdf->save('myfile.pdf');
    }

    /**
     * @return Collection of Page belongs to this assignment
     */
    private function getTargetPages(Assignment $assignment,?array $pageIds=null){
        if(isset($pageIds)){
            $pages = Page::whereIn('id',$pageIds)
                ->where('assignment_id',$assignment->id)
                ->get();
        }else{
            $pages = Page::where('assignment_id',$assignment->id)->get();
        }
        if(!count($pages))
            throw new ErrorMsgException('invalid pages with assignment');
        return $pages;
    }

    /**
     * @return string
     */
    private function storeInSystem(string $generatedFile){

        $mediaName = $this->assignment->name.'-'.Carbon::now()->microsecond . '.pdf';

        Storage::disk(FileSystemServicesClass::getDiskName())
            ->put($this->folderName.'/'.$mediaName, $generatedFile);

        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk().'/'.$this->folderName.'/'.$mediaName;
        return $path;
    }

}
