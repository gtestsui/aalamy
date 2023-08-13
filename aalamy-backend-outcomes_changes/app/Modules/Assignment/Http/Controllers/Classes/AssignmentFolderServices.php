<?php

namespace Modules\Assignment\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use Carbon\Carbon;
use Modules\Assignment\Http\DTO\AssignmentData;
use Modules\RosterAssignment\Http\DTO\RosterAssignmentData;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class AssignmentFolderServices
{

    /**
     * @param string|null $contentType
     * check if $contentType is equal to folder or not
     */
    public static function clientIsNeedContentOfFolders($contentType){
        if(!isset($contentType)||$contentType == 'folder'){
            return true;
        }

        return false;
    }

    /**
     * @param string|null $contentType
     * check if $contentType is equal to assignment or not
     */
    public static function clientIsNeedContentOfAssignments($contentType){
        if(!isset($contentType)||$contentType == 'assignment'){
            return true;
        }

        return false;
    }


}
