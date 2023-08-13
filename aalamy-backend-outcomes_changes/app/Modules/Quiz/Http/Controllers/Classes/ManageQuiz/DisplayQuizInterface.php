<?php


namespace Modules\Quiz\Http\Controllers\Classes\ManageQuiz;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Models\Quiz;

interface DisplayQuizInterface
{


    public function getAllMyQuizzes();

    public function setFilter(FilterQuizData $filterQuizData);


    public function getMyQuizById($id);

    public function getMyQuizByIdOrFail($id);




}
