<?php

namespace Modules\Quiz\Traits\ModelRelations;


use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;
use Modules\Quiz\Models\QuizStudent;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

trait QuizStudentRelations
{

    //Relations
    public function Quiz(){
        return $this->belongsTo(Quiz::class,'quiz_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class,'student_id');
    }

    public function QuizQuestionStudentAnswers(){
        return $this->hasMany(QuizQuestionStudentAnswer::class,'quiz_student_id');
    }

}
