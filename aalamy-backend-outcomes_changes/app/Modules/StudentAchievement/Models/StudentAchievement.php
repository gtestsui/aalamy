<?php

namespace Modules\StudentAchievement\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\StudentAchievement\Traits\ModelRelations\StudentAchievementRelations;

class StudentAchievement extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use StudentAchievementRelations;

    protected $table = 'student_achievements';

    public static function customizedBooted(){}


    protected $fillable=[
        'student_id',
        'user_id',//who add the achievement
        'title',
        'description',
        'file',
        'file_type',
        'is_published_by_educator',
        'is_published_by_school',
        'deleted',
        'deleted_by_cascade',
        'deleted_at'
    ];

    private $mySearchableFields = [
        'title',
        'description',
    ];

    //Attributes
    public function getFileAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return null;
    }


    //Scopes
    public function scopePublished($query,$publishBy,bool $status=true){
        return $query->where('is_published_by_'.$publishBy,$status);
    }

    //Functions
    public function publish($publishBy){
        $columnName = 'is_published_by_';
        $columnName .= $publishBy=='teacher'?'school':$publishBy;
        $this->update([
            $columnName => true,
        ]);
    }

    public function isPublished($publishBy){
        $columnName = 'is_published_by_';
        $columnName .= $publishBy=='teacher'?'school':$publishBy;
        return $this->{$columnName};
    }

}
