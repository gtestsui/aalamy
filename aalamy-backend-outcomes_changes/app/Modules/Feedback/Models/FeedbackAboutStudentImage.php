<?php

namespace Modules\Feedback\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Feedback\Traits\ModelRelations\FeedbackAboutStudentImageRelations;

class FeedbackAboutStudentImage extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use FeedbackAboutStudentImageRelations;

    protected $table = 'feedback_about_student_images';

    public static function customizedBooted(){}


    protected $fillable=[
        'feedback_id',
        'image',

        'deleted',
        'deleted_by_cascade',
        'deleted_at',

    ];

    //Attributes
    public function getImageAttribute($key){
        if(is_null($key))
            return null;
        return baseRoute().$key;
    }



    //Scopes

}
