<?php

namespace Modules\Assignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Models\FlashCard;
use Modules\RosterAssignment\Models\RosterAssignment;

class Assignment extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    protected $table = 'assignments';

    public static function customizedBooted(){}


    protected $fillable=[
        'assignment_folder_id',
        'school_id',
        'educator_id',
        'teacher_id',
        'level_subject_id',
        'unit_id',
        'lesson_id',
        'name',
        'description',
        'is_locked',
        'is_hidden',
        'prevent_request_help',
        'display_mark',
        'is_auto_saved',
        'prevent_moved_between_pages',
        'is_shuffling',
        'timer',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];


    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'FlashCards',
        'Pages',
        'RosterAssignments',
    ];


    private $mySearchableFields = [
        'name',
        'description',
    ];


    //Attributes


    //Relations
    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function Teacher(){
        return $this->belongsTo('Modules\User\Models\Teacher','teacher_id');
    }

    public function LevelSubject(){
        return $this->belongsTo('Modules\Level\Models\LevelSubject','level_subject_id');
    }

    public function Unit(){
        return $this->belongsTo('Modules\Level\Models\Unit','unit_id');
    }

    public function Lesson(){
        return $this->belongsTo('Modules\Level\Models\Lesson','lesson_id');
    }

    public function FeedbackAssignments(){
        return $this->belongsTo('Modules\Feedback\Models\FeedbackAboutStudent','assignment_id');
    }

    public function FlashCards(){
        return $this->hasMany(FlashCard::class,'assignment_id');
    }

    public function Pages(){
        return $this->hasMany(Page::class,'assignment_id');
    }

    public function RosterAssignments(){
        return $this->hasMany(RosterAssignment::class,'assignment_id');
    }

    public function AssignmentFolder(){
        return $this->belongsTo(AssignmentFolder::class,'assignment_folder_id');
    }

    //Scopes
    public function scopeIsLocked($query,$status=false){
        return $query->where('is_locked',$status);
    }


    public function scopeIsHidden($query,$status=false){
        return $query->where('is_hidden',$status);
    }

    //functions
    public function checkIsHidden(){
        if($this->is_hidden)
            return true;
        return false;
    }

    public function checkIsLocked(){
        if($this->is_locked)
            return true;
        return false;
    }

}
