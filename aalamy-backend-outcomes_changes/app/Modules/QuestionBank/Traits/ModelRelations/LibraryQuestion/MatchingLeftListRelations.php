<?php

namespace Modules\QuestionBank\Traits\ModelRelations\LibraryQuestion;


use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionMatchingRightList;


trait MatchingLeftListRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(LibraryQuestion::class,'library_question_id');
    }

    public function RightListRecords(){
        return $this->hasMany(LibraryQuestionMatchingRightList::class,'left_list_id');
    }

}
