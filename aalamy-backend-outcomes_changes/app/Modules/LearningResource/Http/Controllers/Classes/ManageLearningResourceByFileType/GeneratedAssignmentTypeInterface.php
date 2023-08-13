<?php


namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByFileType;


use Modules\Assignment\Models\Assignment;

interface GeneratedAssignmentTypeInterface
{

    public function __construct(Assignment $assignment);

    /**
     * @note the main job of this function is generate file from full assignment paes
     * or defined pages and store it in the system and return the path of that file
     * @return string
     */
    public function generate(?array $page_ids=null);


}
