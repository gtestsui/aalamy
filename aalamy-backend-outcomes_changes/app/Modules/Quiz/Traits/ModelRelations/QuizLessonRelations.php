<?php

namespace Modules\Quiz\Traits\ModelRelations;


use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizStudent;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait QuizLessonRelations
{

    //Relations
    public function Quiz(){
        return $this->belongsTo(Quiz::class,'quiz_id');
    }
    public function Lesson(){
        return $this->belongsTo(Lesson::class,'lesson_id');
    }

}
