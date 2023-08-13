<?php

namespace Modules\SchoolInvitation\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Modules\Notification\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SchoolInvitation\Traits\ModelRelations\SchoolTeacherInvitationRelations;

class SchoolTeacherInvitation extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use SchoolTeacherInvitationRelations;

    public static function customizedBooted(){}


    protected $fillable=[
      'school_id',
//      'type',
      'link',
//      'expire_date',
      'teacher_email',
      'deleted',
      'deleted_by_cascade',
      'deleted_at',
    ];

    protected $mySearchableFields = [
        'teacher_email',

    ];

}
