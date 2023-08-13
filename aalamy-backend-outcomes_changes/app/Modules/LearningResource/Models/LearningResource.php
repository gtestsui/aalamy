<?php

namespace Modules\LearningResource\Models;

use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\MyOwnAndMyAllowedpermission;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use App\Scopes\WithoutDeletedItemsScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\LearningResource\Traits\ModelRelations\Topic\LearningReourceRelations;
use Modules\User\Http\Controllers\Classes\UserServices;

class LearningResource extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
//    use MyOwnAndMyAllowedpermission;
    use LearningReourceRelations;
    protected $table = 'learning_resources';

    public static function customizedBooted(){


        //        /**
//         * @see removeWithoutDeletedItemsScope
//         */
//        if(UserServices::checkShouldRemoveWithoutDeletedItemsScope())
//            static::removeWithoutDeletedItemsScope();


    }


    protected $fillable=[
        'user_id',//the real user who add the topic
        'school_id',
        'teacher_id',
        'educator_id',
        'topic_id',
        'share_type',
        'level_subject_id',
        'unit_id',
        'lesson_id',
        'name',
        'file',
        'file_type',
        'assignment_id',
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
//        'AllReplies',//we commented this relations because you can't reach them from anywhere if the post is deleted
//        'Pictures',
//        'Files',

    ];

    protected $mySearchableFields = [
        'share_type',
        'name',
        'file_type',
    ];

    //Attributes
    public function getFileAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return null;
    }


    //Scopes
    public function scopeSharedAsPublic($query){

        return $query->where('share_type',configFromModule(
            'panel.learning_resource_read_share_types.public',
            ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
        ));
    }

    //Educator
    public function scopeMyAllowedAsEducator($query,$educatorId,$userId,$myTeacherAccountIds,$myTeacherSchoolIds){
        return $query->where(function ($query)use($educatorId,$myTeacherAccountIds,$myTeacherSchoolIds,$userId){

            return $query
                ->myOwnAsEducator($educatorId,$myTeacherAccountIds,$userId)
                ->orWhere(function ($query)use ($myTeacherSchoolIds,$userId){
                    return $query->allowedFromMySchool($myTeacherSchoolIds,$userId);
                })
                ->orWhere(function ($query){
                    return $query->sharedAsPublic();
                });
        });
    }

    public function scopeMyOwnAsEducator($query,$educatorId,$myTeacherAccounts,$userId){
        return $query->where(function ($query)use ($educatorId,$myTeacherAccounts,$userId){
            return $query->where('educator_id',$educatorId)
                ->orWhereIn('teacher_id',$myTeacherAccounts)
                ->orWhere('user_id',$userId);

        });
    }

    //School
    public function scopeMyAllowedAsSchool($query,$schoolId,$userId){
        return $query->where(function ($query)use ($schoolId,$userId){
            return $query
                ->myOwnAsSchool($schoolId)
                ->orWhere(function ($query)use ($schoolId,$userId){
                    return $query->allowedFromMySchool($schoolId,$userId);
                })
                ->orWhere(function ($query){
                    return $query->sharedAsPublic();
                });
        });
    }

    public function scopeMyOwnAsSchool($query,$schoolId){
        return $query->where(function ($query)use ($schoolId){
            return $query->where('school_id',$schoolId)
                ->whereNull('teacher_id');
        });
    }

    //Student
    public function scopeMyAllowedAsStudent($query,$mySchoolId,$myEducatorIds){
        return $query->where(function ($query)use ($mySchoolId,$myEducatorIds){
            return $query
                ->where(function ($query)use ($mySchoolId){
                    return $query->allowedFromMySchool($mySchoolId,-1);
                })
                ->orWhere(function ($query)use ($myEducatorIds){
                    return $query->allowedFromMyEducator($myEducatorIds);
                })
                ->orWhere(function ($query){
                    return $query->sharedAsPublic();
                });
        });
    }



    public function scopeAllowedFromMySchool($query,$schoolId,$userId){
        return $query->where(function ($query)use ($schoolId,$userId){
            if(is_array($schoolId)){
                $query->whereIn('school_id',$schoolId);
            }else{
                $query->where('school_id',$schoolId);
            }

            return $query->where(function ($query)use($userId) {
                return $query->where('user_id',$userId)
                    ->orWhere('share_type',configFromModule(
                    'panel.learning_resource_read_share_types.school',
                    ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
                ));
            });

//                return $query->where('share_type',configFromModule(
//                        'panel.learning_resource_read_share_types.school',
//                        ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
//                    )
//                );
        });

    }

    public function scopeAllowedFromMyEducator($query,$myEducatorIds){

        return $query->where(function ($query)use ($myEducatorIds){
            if(is_array($myEducatorIds)){
                $query->whereIn('educator_id',$myEducatorIds);
            }else{
                $query->where('educator_id',$myEducatorIds);
            }
            return $query->where('share_type',configFromModule(
                'panel.learning_resource_read_share_types.my_private_student',
                ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
            ));
        });

    }


    //Functions


}
