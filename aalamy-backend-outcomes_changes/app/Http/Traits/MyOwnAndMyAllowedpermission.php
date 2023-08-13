<?php


namespace App\Http\Traits;


trait MyOwnAndMyAllowedpermission
{


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

}
