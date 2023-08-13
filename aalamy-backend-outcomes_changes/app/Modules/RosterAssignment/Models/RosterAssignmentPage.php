<?php

namespace Modules\RosterAssignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Assignment\Models\Page;

class RosterAssignmentPage extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    protected $table = 'roster_assignment_pages';

    public static function customizedBooted(){}


    protected $fillable=[
        'page_id',
        'roster_assignment_id',

        'is_hidden',
        'is_locked',

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
        'RosterAssignmentStudentPages',
    ];

    //Attributes



    //Relations
    public function Page(){
        return $this->belongsTo(Page::class,'page_id');
    }

    public function RosterAssignment(){
        return $this->belongsTo(RosterAssignment::class,'roster_assignment_id');
    }

    public function RosterAssignmentStudentPages(){
        return $this->hasMany(RosterAssignmentStudentPage::class,'roster_assignment_page_id');
    }

    //Scopes
    public function scopeIsLocked($query,$status=true){
        return $query->where('is_locked',$status);
    }


    public function scopeIsHidden($query,$status=true){
        return $query->where('is_hidden',$status);
    }

    public function scopeIHaveAccessToDisplay($query,$studentId){
        return $query->isHidden(false)
            ->where(function ($query)use ($studentId){
                return $query->whereHas('RosterAssignmentStudentPages',function ($query)use ($studentId){
                    return $query->where('student_id',$studentId)
                        ->isHidden(false);
                })
                ->orWhereDoesntHave('RosterAssignmentStudentPages',function ($query)use ($studentId){
                    return $query->where('student_id',$studentId);
                });
            });
    }


//    /**
//     * update the is_locked and make it false
//     */
//    public function scopeUnLock($query){
//        return $query->lock(false);
//    }
//
//    /**
//     * update the is_locked and make it true
//     */
//    public function scopeLock($query,$status=true){
//        return $query->update([
//            'is_locked' => $status
//        ]);
//    }
//
//    /**
//     * update the is_hidden and make it false
//     */
//    public function scopeUnHide($query){
//        return $query->hide(false);
//    }
//
//    /**
//     * update the is_hidden and make it true
//     */
//    public function scopeHide($query,$status=true){
//        return $query->update([
//            'is_hidden' => $status
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
