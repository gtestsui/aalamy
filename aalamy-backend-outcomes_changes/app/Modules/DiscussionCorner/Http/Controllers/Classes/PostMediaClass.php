<?php


namespace Modules\DiscussionCorner\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Illuminate\Http\UploadedFile;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerPostFile;
use Modules\DiscussionCorner\Models\DiscussionCornerPostPicture;
use Modules\DiscussionCorner\Models\DiscussionCornerPostVideo;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;

class PostMediaClass
{

    /**
     * @var DiscussionCornerPost $post
     */
    private $post;
    public function __construct(DiscussionCornerPost $post){
        $this->post = $post;
    }

    public  function addMoreThanPictureToPost(?array $pictures){
        if(isset($pictures)){
            foreach ($pictures as $picture){
                $this->addPictureToPost($picture);
            }
        }
    }

    public function addPictureToPost(UploadedFile $picture):DiscussionCornerPostPicture
    {
        $path = FileManagmentServicesClass::storeFiles($picture,"discussion_post_images/{$this->post->user_id}/{$this->post->id}");
//        $path = ServicesClass::storeFiles($picture,"discussion_post_images/{$this->post->user_id}/{$this->post->id}");
        return DiscussionCornerPostPicture::create([
            'post_id' =>  $this->post->id,
            'picture' =>  $path,
        ]);
    }

    public function deletePictures(?array $pictureIds){
        if(is_null($pictureIds))
            return true;
        //$sharedPictureIds -> [picture => id,...]
        $sharedPictureIds = DiscussionCornerPostPicture::where('post_id',$this->post->id)
            ->whereIn('id',$pictureIds)->pluck('id','picture')->toArray();
        DiscussionCornerPostPicture::whereIn('id',$sharedPictureIds)->delete();
        FileManagmentServicesClass::deleteFiles(array_keys($sharedPictureIds));
    }

    public function addMoreThanVideoToPost(?array $videos){
        if(isset($videos)){
            foreach ($videos as $video){
                $this->addVideoToPost($video);
            }
        }
    }

    public function addVideoToPost(UploadedFile $video):DiscussionCornerPostVideo
    {
        $path = FileManagmentServicesClass::storeFiles($video,"discussion_post_videos/{$this->post->user_id}/{$this->post->id}");
//        $path = ServicesClass::storeFiles($video,"discussion_post_videos/{$this->post->user_id}/{$this->post->id}");
        return DiscussionCornerPostVideo::create([
            'post_id' =>  $this->post->id,
            'video' =>  $path,
        ]);
    }

    public function deleteVideos(?array $videoIds){
        if(is_null($videoIds))
            return true;
        $sharedVideoIds = DiscussionCornerPostVideo::where('post_id',$this->post->id)
            ->whereIn('id',$videoIds)->pluck('id','video')->toArray();
        DiscussionCornerPostVideo::whereIn('id',$sharedVideoIds)->delete();
        FileManagmentServicesClass::deleteFiles(array_keys($sharedVideoIds));
    }

    public function addMoreThanFileToPost(?array $files){
        if(isset($files)){
            foreach ($files as $file){
                $this->addFileToPost($file);
            }
        }
    }

    public function addFileToPost(UploadedFile $file):DiscussionCornerPostFile
    {
        $path = FileManagmentServicesClass::storeFiles($file,"discussion_post_files/{$this->post->user_id}/{$this->post->id}");
//        $path = ServicesClass::storeFiles($file,"discussion_post_files/{$this->post->user_id}/{$this->post->id}");
        return DiscussionCornerPostFile::create([
            'post_id' =>  $this->post->id,
            'file' =>  $path,
        ]);
    }

    public function deleteFiles(?array $fileIds){
        if(is_null($fileIds))
            return true;
        $sharedFileIds = DiscussionCornerPostFile::where('post_id',$this->post->id)
            ->whereIn('id',$fileIds)->pluck('id','file')->toArray();
        DiscussionCornerPostFile::whereIn('id',$sharedFileIds)->delete();
        FileManagmentServicesClass::deleteFiles(array_keys($sharedFileIds));
    }


}
