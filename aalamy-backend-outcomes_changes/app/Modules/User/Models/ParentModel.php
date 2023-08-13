<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\ModelRelations\ParentRelations;

class ParentModel extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use SoftDelete;
    use ParentRelations;


    protected $table = 'parents';

    public static function customizedBooted(){}


    protected $fillable = [
        'user_id',
        'is_active',
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
        'User',
        'ParentStudents',
        'TargetUsers',
        'MeetingTargetUsers',

    ];


    //Scopes
    public function scopeActive($query,$status=true){
        return $query->where('is_active',$status);
    }

    public function scopeHasStudent($query,$studentId){
        if(!is_array($studentId)){
            return $query->whereHas('ParentStudents',function ($query)use ($studentId){
                return $query->where('student_id',$studentId);
            });
        }else{
            return $query->whereHas('ParentStudents',function ($query)use ($studentId){
                return $query->whereIn('student_id',$studentId);
            });
        }

    }


    //Functions
    public function activateOrDeActivate(){
        if(!$this->is_active)
            return $this->update([
                'is_active' => 1
            ]);
        else
            return $this->update([
                'is_active' => 0
            ]);
    }

    public function activate(){
        return $this->update([
            'is_active' => 1
        ]);
    }

    public function deactivate(){
        return $this->update([
            'is_active' => 0
        ]);
    }





}
