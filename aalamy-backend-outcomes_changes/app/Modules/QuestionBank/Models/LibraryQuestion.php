<?php

namespace Modules\QuestionBank\Models;

use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\MyOwnAndMyAllowedpermission;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionBank\Traits\ModelRelations\LibraryQuestion\LibraryQuestionRelations;

class LibraryQuestion extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
//    use MyOwnAndMyAllowedpermission;
    use LibraryQuestionRelations;

    protected $table = 'library_questions';

    public static function customizedBooted(){}


    protected $fillable=[
        'question',
        'question_type',
        'difficult_level',
        'share_type',//when the item updated then shared_with_library will reset to default false and can share it again
        'school_id',
        'educator_id',
        'teacher_id',
        'level_subject_id',
        'unit_id',
        'lesson_id',
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
        'FillInBlanks',
        'FillTexts',
        'JumbleSentences',
        'MatchingLeftList',
        'MatchingRightList',
        'MultiChoices',
        'Ordering',
        'TrueFalse',

    ];

    private $mySearchableFields = [
        'question',
        'question_type',
    ];

    //Attributes



    //Scopes

    //Educator
    public function scopeMyAllowedAsEducator($query,$educatorId,$myTeacherAccountIds,$myTeacherSchoolIds){
        return $query->where(function ($query)use($educatorId,$myTeacherAccountIds,$myTeacherSchoolIds){

            return $query
                ->myOwnAsEducator($educatorId,$myTeacherAccountIds)
                ->orWhere(function ($query)use ($myTeacherSchoolIds){
                    return $query->allowedFromMySchool($myTeacherSchoolIds);
                })
                ->orWhere(function ($query){
                    return $query->sharedAsPublic();
                });
        });
    }

    public function scopeMyOwnAsEducator($query,$educatorId,$myTeacherAccounts){
        return $query->where(function ($query)use ($educatorId,$myTeacherAccounts){
            return $query->where('educator_id',$educatorId)
                ->orWhereIn('teacher_id',$myTeacherAccounts);
        });
    }

    //School
    public function scopeMyAllowedAsSchool($query,$schoolId){
        return $query->where(function ($query)use ($schoolId){
            return $query
                ->myOwnAsSchool($schoolId)
                ->orWhere(function ($query)use ($schoolId){
                    return $query->allowedFromMySchool($schoolId);
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
                    return $query->allowedFromMySchool($mySchoolId);
                })
                ->orWhere(function ($query)use ($myEducatorIds){
                    return $query->allowedFromMyEducator($myEducatorIds);
                })
                ->orWhere(function ($query){
                    return $query->sharedAsPublic();
                });
        });
    }


    public function scopeSharedAsPublic($query){

        return $query->where('share_type',configFromModule(
            'panel.question_share_types_with_library.public',
            ApplicationModules::QUESTION_BANK_MODULE_NAME
        ));
    }

    public function scopeAllowedFromMySchool($query,$schoolId){
        return $query->where(function ($query)use ($schoolId){
            if(is_array($schoolId)){
                $query->whereIn('school_id',$schoolId);
            }else{
                $query->where('school_id',$schoolId);
            }
            return $query->where('share_type',configFromModule(
                        'panel.question_share_types_with_library.school',
                        ApplicationModules::QUESTION_BANK_MODULE_NAME
                    )
                );
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
                    'panel.question_share_types_with_library.my_private_student',
                    ApplicationModules::QUESTION_BANK_MODULE_NAME
                ));
        });

    }







    public function scopeFilterMyQuestionLibrary($query,?array $filter=null){
        if(is_null($filter) || !count($filter))
            return $query;

        $query->when(isset($filter['search_key']),function ($query)use ($filter){
                return $query->search($filter['search_key']);
            })->when(isset($filter['question_type']),function ($query)use ($filter){
                return $query->where('question_type',$filter['question_type']);
            })
            ->when(isset($filter['level_id']),function ($query)use ($filter){
                return $query->whereHas('LevelSubject',function ($query)use ($filter){
                    return $query->where('level_id',$filter['level_id']);
                });
            })
            ->when(isset($filter['level_subject_id']),function ($query)use ($filter){
                return $query->where('level_subject_id',$filter['level_subject_id']);
            })
            ->when(isset($filter['unit_id']),function ($query)use ($filter){
                return $query->where('unit_id',$filter['unit_id']);
            })
            ->when(isset($filter['lesson_id']),function ($query)use ($filter){
                return $query->where('lesson_id',$filter['lesson_id']);
            })


            ->when(isset($filter['level_name']),function ($query)use ($filter){
                return $query->whereHas('LevelSubject',function ($query)use ($filter){
                    return $query->whereHas('Level',function ($query)use ($filter){
                        return $query->where('name',$filter['level_name']);
                    });
                });
            })
            ->when(isset($filter['subject_name']),function ($query)use ($filter){
                return $query->whereHas('LevelSubject',function ($query)use ($filter){
                    return $query->whereHas('Subject',function ($query)use ($filter){
                        return $query->where('name',$filter['subject_name']);
                    });
                });
            })
            ->when(isset($filter['unit_name']),function ($query)use ($filter){
                return $query->whereHas('Unit',function ($query)use ($filter){
                    return $query->where('name',$filter['unit_name']);
                });
            })
            ->when(isset($filter['lesson_name']),function ($query)use ($filter){
                return $query->whereHas('Lesson',function ($query)use ($filter){
                    return $query->where('name',$filter['lesson_name']);
                });
            })

            ->when(isset($filter['difficult_levels']),function ($query)use ($filter){
                return $query->whereIn('difficult_level',$filter['difficult_levels']);
            });
        return $query;
    }


    //Function

    public function markAsShared($status=true){
        return $this->update([
            'shared_with_library'=>$status
        ]);
    }


    public function isShared(){
        return $this->shared_with_library;
    }


}
