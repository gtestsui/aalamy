<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType;



use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;

interface ManageDiscussionCornerByTypeInterface
{

   public function getRandomPostsPaginate();
   public function getRandomSurveysPaginate();

    /**
     * @return DiscussionCornerPost
     */
   public function getPostIHaveAccessToSeeById($id);

    /**
     * @throws ModelNotFoundException
     * @return DiscussionCornerPost
     */
    public function getPostIHaveAccessToSeeByIdOrFail($id);

}
