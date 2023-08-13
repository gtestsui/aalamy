<?php


namespace Modules\Feedback\Http\Controllers\Classes\ManageFeedback;



use Illuminate\Database\Eloquent\Collection;
use Modules\Feedback\Models\FeedbackAboutStudent;

interface ManageFeedbackAboutStudentInterface
{

    /**
     * @param int $studentId
     * throw error if the student its not belong
     */
    public function checkAddFeedbackAboutStudent($studentId);

    /**
     * @param FeedbackAboutStudent $feedback
     * throw error if the $feedback its not belong
     */
    public function checkUpdateFeedbackAboutStudent(FeedbackAboutStudent $feedback);

    /**
     * @param FeedbackAboutStudent $feedback
     * throw error if the $feedback its not belong
     */
    public function checkDeleteFeedbackAboutStudent(FeedbackAboutStudent $feedback);

//
//    /**
//     * @return Collection of FeedbackAboutStudent
//     */
//    public function getAllMyFeedback();
//
//
//    /**
//     * @return Collection of FeedbackAboutStudent
//     */
//    public function getMyFeedbackPaginate();
//

}
