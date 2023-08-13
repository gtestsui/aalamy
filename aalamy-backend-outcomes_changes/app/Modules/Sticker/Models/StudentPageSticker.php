<?php

namespace Modules\Sticker\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Sticker\Traits\ModelRelations\StudentPageStickerRelations;

class StudentPageSticker extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use StudentPageStickerRelations;

    protected $table = 'student_page_stickers';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'educator_id',
        'teacher_id',

        'roster_assignment_student_page_id',
        'roster_assignment_id',
        'sticker_id',
        'page_id',
        'student_id',

        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [

    ];

    //Attributes

    //Scopes


}
