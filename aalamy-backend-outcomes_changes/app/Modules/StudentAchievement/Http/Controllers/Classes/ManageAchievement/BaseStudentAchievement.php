<?php


namespace Modules\StudentAchievement\Http\Controllers\Classes\ManageAchievement;


use Illuminate\Pagination\LengthAwarePaginator;
use Modules\StudentAchievement\Http\Requests\StudentAchievement\StoreStudentAchievementRequest;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Models\User;

abstract class BaseStudentAchievement
{

    public abstract function checkStoreAchievementAuthorization($studentId): Void;

    /**
     * @param array $userIds
     * @return LengthAwarePaginator
     */
    public function getStudentAchievementWaitingToPublishByUserIds(array $studentIds,array $parentUserIds){
        $studentAchievements = StudentAchievement::whereIn('student_id',$studentIds)
            ->whereIn('user_id',$parentUserIds)
            ->published($this->publishBy,false)
            ->with(['User','Student.User'])
            ->paginate(10);
        return $studentAchievements;
    }


}
