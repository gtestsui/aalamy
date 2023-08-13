<?php

namespace Modules\LearningResource\Models;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\DefaultGlobalScopes;
use App\Http\Traits\MyOwnAndMyAllowedpermission;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use App\Scopes\WithoutDeletedItemsScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Traits\ModelRelations\Topic\TopicRelations;
use Modules\User\Http\Controllers\Classes\UserServices;

class Topic extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
//    use MyOwnAndMyAllowedpermission;
    use TopicRelations;
    protected $table = 'topics';



    public static function customizedBooted(){

//        //there is some models not booted so when calling middleware RemoveWithoutDeletedItemsScopeMiddleware
//        //the  static::$globalScopes for these models will not have been initialized
//        //,and it will be initialized after the request resolver(have logged user object and the route parameters)
//        if( static::$withoutDeletedItemsScopeHasRemoved == false
//            &&!is_null(request()->route('soft_delete'))
//            && UserServices::isSuperAdmin(request()->user())){
//
//            unset(static::$globalScopes[static::class][WithoutDeletedItemsScope::class]);
//        }

//    dd(static::$globalScopes['Modules\LearningResource\Models\Topic']??null);
        //        /**
//         * @see removeWithoutDeletedItemsScope
//         */
//        if(UserServices::checkShouldRemoveWithoutDeletedItemsScope())
//            static::removeWithoutDeletedItemsScope();

    }


    protected $fillable=[
        'read_share_type',
        'write_share_type',
//        'share_types',
        'user_id',//the real user who add the topic
        'topic_id',
        'school_id',
        'teacher_id',
        'educator_id',
        'name',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];



    protected $mySearchableFields = [
        'read_share_type',
        'write_share_type',
        'name',
    ];


    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'Topics',
        'LearningResources',

    ];

    //Attributes
//    public function getShareTypesAttribute($key){
//        return json_decode($key);
//    }

    /**
     * @param array $value
     * @return json
     */
//    public function setShareTypesAttribute($value){
//        $this->attributes['share_types'] = json_encode($value);
//    }


    //Scopes
    public function scopeIsRoot($query){
        return $query->whereNull('topic_id');
    }


    //Educator
    public function scopeMyAllowedAsEducator($query,$educatorId,$userId,$myTeacherAccountIds,$myTeacherSchoolIds,string $accessType){
        LearningResourceServices::checkValidTopicAccessType($accessType);

        return $query->where(function ($query)use($educatorId,$userId,$myTeacherAccountIds,$myTeacherSchoolIds,$accessType){

            return $query
                ->myOwnAsEducator($educatorId,$myTeacherAccountIds,$userId)
                ->orWhere(function ($query)use ($myTeacherSchoolIds,$accessType,$userId){
                    return $query->allowedFromMySchool($myTeacherSchoolIds,$userId,$accessType);
                })
                ->orWhere(function ($query)use ($accessType){
                    return $query->sharedAsPublic($accessType);
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

//    public function scopeMyOwnAsEducatorJustForDisplay($query,$educatorId,$myTeacherAccounts,$userId){
//        return $query->where(function ($query)use ($educatorId,$myTeacherAccounts,$userId){
//            return $query->where('educator_id',$educatorId)
//                /*->orWhereIn('teacher_id',$myTeacherAccounts)
//                ->orWhere('user_id',$userId)*/;
//        });
//    }

    public function scopeMyAllowedAsSchool($query,$schoolId,$userId,string $accessType){
        LearningResourceServices::checkValidTopicAccessType($accessType);

        return $query->where(function ($query)use ($schoolId,$userId,$accessType){
            return $query
                ->myOwnAsSchool($schoolId,$userId)
                ->orWhere(function ($query)use ($schoolId,$accessType,$userId){
                    return $query->allowedFromMySchool($schoolId,$userId,$accessType);
                })
                ->orWhere(function ($query)use ($accessType){
                    return $query->sharedAsPublic($accessType);
                });
        });
    }

    public function scopeMyOwnAsSchool($query,$schoolId,$userId){
        return $query->where(function ($query)use ($schoolId,$userId){
            return $query->where('school_id',$schoolId)
                ->whereNull('teacher_id')
                ->orWhere('user_id',$userId);
        });
    }

//    public function scopeMyOwnAsSchoolJustForDisplay($query,$schoolId,$userId){
//        return $query->where(function ($query)use ($schoolId,$userId){
//            return $query->where('school_id',$schoolId)
//                /*->whereNull('teacher_id')
//                ->orWhere('user_id',$userId)*/;
//        });
//    }

    public function scopeAllowedFromMySchool($query,$schoolId,$userId,string $accessType=TopicConstants::READ_ACCESS_TYPE){
        LearningResourceServices::checkValidTopicAccessType($accessType);

        if(is_array($schoolId)){
            $query->whereIn('school_id',$schoolId);
        }else{
            $query->where('school_id',$schoolId);
        }

        return $query->where(function ($query)use($userId,$accessType){
            return $query->where('user_id',$userId)
                ->orWhere($accessType.'_share_type',configFromModule(
                    'panel.learning_resource_read_share_types.school',
                    ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
                ));
        });

//        return $query->where(function ($query)use ($schoolId,$accessType,$userId){
//            if(is_array($schoolId)){
//                $query->whereIn('school_id',$schoolId);
//            }else{
//                $query->where('school_id',$schoolId);
//            }
//
//            return $query->where($accessType.'_share_type',configFromModule(
//                'panel.learning_resource_read_share_types.school',
//                ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
//            ));
//            });
//
//
//        });

    }

    public function scopeAllowedFromMyEducator($query,$myEducatorIds,string $accessType=TopicConstants::READ_ACCESS_TYPE){
        LearningResourceServices::checkValidTopicAccessType($accessType);

        return $query->where(function ($query)use ($myEducatorIds,$accessType){
            if(is_array($myEducatorIds)){
                $query->whereIn('educator_id',$myEducatorIds);
            }else{
                $query->where('educator_id',$myEducatorIds);
            }
            return $query->where($accessType.'_share_type',configFromModule(
                'panel.learning_resource_read_share_types.my_private_student',
                ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
            ));
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


    public function scopeSharedAsPublic($query,string $accessType=TopicConstants::READ_ACCESS_TYPE){
        LearningResourceServices::checkValidTopicAccessType($accessType);
        return $query->where($accessType.'_share_type',configFromModule(
            'panel.learning_resource_read_share_types.public',
            ApplicationModules::LEARNING_RESOURCE_MODULE_NAME
        ));
    }



    //Functions


}
