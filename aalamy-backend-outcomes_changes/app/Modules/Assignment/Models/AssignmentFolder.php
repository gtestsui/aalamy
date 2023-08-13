<?php

namespace Modules\Assignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Models\FlashCard;
use Modules\RosterAssignment\Models\RosterAssignment;

class AssignmentFolder extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    protected $table = 'assignment_folders';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'educator_id',
        'teacher_id',
        'parent_id',
        'name',
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
        'Assignments',
        'Children',
    ];


    private $mySearchableFields = [
        'name',
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

    public function Parent(){
        return $this->belongsTo(AssignmentFolder::class,'parent_id');
    }

    public function Children(){
        return $this->hasMany(AssignmentFolder::class,'parent_id');
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'assignment_folder_id');
    }

    public function AllParents(){
        return $this->Parent()->with('AllParents');
    }

    //Scopes
    public function scopeIsRoot($query){
        return $query->whereNull('parent_id');
    }



}
