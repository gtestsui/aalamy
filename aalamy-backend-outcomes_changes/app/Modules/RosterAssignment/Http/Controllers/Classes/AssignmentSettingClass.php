<?php

namespace Modules\RosterAssignment\Http\Controllers\Classes;


use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Models\RosterAssignment;


class AssignmentSettingClass{


    public $isLocked;
    public $isHidden;
    public $preventRequestHelp;
    public $displayMark;
    public $isAutoSaved;
    public $preventMovedBetweenPages;
    public $isShuffling;

    public function __construct($request){

        $this->isLocked                 = isset($request->is_locked)?(bool)$request->is_locked:config('Assignment.panel.assignment.is_locked_default');
        $this->isHidden                 = isset($request->is_hidden)?(bool)$request->is_hidden:config('Assignment.panel.assignment.is_hidden_default');
        $this->preventRequestHelp       = isset($request->prevent_request_help)?(bool)$request->prevent_request_help:config('Assignment.panel.assignment.prevent_request_help_default');
        $this->displayMark              = isset($request->display_mark)?(bool)$request->display_mark:config('Assignment.panel.assignment.display_mark_default');
        $this->isAutoSaved              = isset($request->is_auto_saved)?(bool)$request->is_auto_saved:config('Assignment.panel.assignment.is_auto_saved_default');
        $this->preventMovedBetweenPages = isset($request->prevent_moved_between_pages)?(bool)$request->prevent_moved_between_pages:config('Assignment.panel.assignment.prevent_moved_between_pages_default');
        $this->isShuffling              = isset($request->is_shuffling)?(bool)$request->is_shuffling:config('Assignment.panel.assignment.is_shuffling_default');
    }

    /**
     * @param RosterAssignment|Assignment $assignment
     * @return self
     */
    public function prepareAssignmentSetting($assignment,$request){
        $this->isLocked = isset($request->is_locked)
            ?(bool)$request->is_locked
            :(bool)$assignment->is_locked;

        $this->isHidden = isset($request->is_hidden)
            ?(bool)$request->is_hidden
            :(bool)$assignment->is_hidden;

        $this->preventRequestHelp = isset($request->prevent_request_help)
            ?(bool)$request->prevent_request_help
            :(bool)$assignment->prevent_request_help;

        $this->displayMark = isset($request->display_mark)
            ?(bool)$request->display_mark
            :(bool)$assignment->display_mark;

        $this->isAutoSaved = isset($request->is_auto_saved)
            ?(bool)$request->is_auto_saved
            :(bool)$assignment->is_auto_saved;

        $this->preventMovedBetweenPages = isset($request->prevent_moved_between_pages)
            ?(bool)$request->prevent_moved_between_pages
            :(bool)$assignment->prevent_moved_between_pages;

        $this->isShuffling = isset($request->is_shuffling)
            ?(bool)$request->is_shuffling
            :(bool)$assignment->is_shuffling;

        return $this;
    }

    /**
     * @return array
     */
    public function all(){
        return [
            'is_locked'             =>$this->isLocked,
            'is_hidden'             =>$this->isHidden,
            'prevent_request_help'  =>$this->preventRequestHelp,
            'display_mark'          =>$this->displayMark,
            'is_auto_saved'         =>$this->isAutoSaved,
            'prevent_moved_between_pages' =>$this->preventMovedBetweenPages,
            'is_shuffling'          =>$this->isShuffling,
        ];
    }


}
