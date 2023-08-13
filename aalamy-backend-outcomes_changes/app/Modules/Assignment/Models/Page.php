<?php

namespace Modules\Assignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;

class Page extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    protected $table = 'pages';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByPriority', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)
                ->orderBy('order', 'asc')
                ->orderBy('id');
        });
    }


    protected $fillable=[
        'assignment_id',
//        'default_empty_page_id',
        'page',
        'is_empty',
        'is_hidden',
        'is_locked',
        'timer',
        'order',
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
        'RosterAssignmentPages',
    ];

    //Attributes
    public function getPageAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return null;
    }


    //Relations
    public function Assignment(){
        return $this->belongsTo(Assignment::class,'assignment_id');
    }

//    public function StudentPages(){
//        return $this->hasMany(RosterAssignmentStudentPage::class,'roster_assignment_page_id');
//    }

    public function RosterAssignmentPages(){
        return $this->hasMany(RosterAssignmentPage::class,'page_id');
    }


    //Scopes
    public function scopeIsLocked($query,$status=true){
        return $query->where('is_locked',$status);
    }


    public function scopeIsHidden($query,$status=true){
        return $query->where('is_hidden',$status);
    }

//    public function scopeLock($query){
//        return $query->update([
//            'is_locked' => true
//        ]);
//    }

//    public function scopeUnHide($query){
//        return $query->hide(false);
//    }

//    public function scopeHide($query,$status=true){
//        return $query->update([
//           'is_hidden' => $status
//        ]);
//    }

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


    public function hideOrUnHide(){
        $this->update([
            'is_hidden' => !$this->is_hidden,
        ]);
    }

    public function lockOrUnLock(){
        $this->update([
            'is_locked' => !$this->is_locked,
        ]);
    }

}
