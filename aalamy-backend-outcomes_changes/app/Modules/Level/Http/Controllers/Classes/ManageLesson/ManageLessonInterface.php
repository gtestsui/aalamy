<?php


namespace App\Modules\Level\Http\Controllers\Classes\ManageLesson;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ManageLessonInterface
{


    public function myLessonsPaginate(): LengthAwarePaginator;

    /**
     * @return Collection of Lesson model
     */
    public function myLessonsAll(): Collection;

    /**
     * @return Collection of Lesson model
     */
    public function myLessonsById(?int $lessonId): Collection;

}
