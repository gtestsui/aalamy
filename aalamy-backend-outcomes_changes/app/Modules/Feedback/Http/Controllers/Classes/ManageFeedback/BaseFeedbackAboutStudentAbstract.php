<?php


namespace Modules\Feedback\Http\Controllers\Classes\ManageFeedback;



use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Feedback\Models\FeedbackAboutStudent;

abstract class BaseFeedbackAboutStudentAbstract
{


    abstract protected function getMyFeedbackQuery();

    /**
     * @return LengthAwarePaginator of FeedbackAboutStudent
     */
    public function getMyFeedbackPaginate(){
        $myFeedbackQuery = $this->getMyFeedbackQuery();
        $myFeedback = $myFeedbackQuery
            ->withAllRelations()
            ->paginate(config('Feedback.panel.feedback_paginate_num'));
        return $myFeedback;
    }

    /**
     * @return Collection of FeedbackAboutStudent
     */
    public function getAllMyFeedback(){
        $myFeedbackQuery = $this->getMyFeedbackQuery();
        $myFeedback = $myFeedbackQuery->get();
        return $myFeedback;
    }


}
