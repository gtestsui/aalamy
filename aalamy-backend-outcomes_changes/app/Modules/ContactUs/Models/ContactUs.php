<?php

namespace Modules\ContactUs\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ContactUs\Traits\ModelRelations\ContactUsRelations;
use Modules\ContactUs\Traits\ModelRelations\EventRelations;

class ContactUs extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use SoftDelete;
//    use SoftDeletes;
    use ContactUsRelations;
    protected $table = 'contact_us';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'subject',
        'text',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    protected $mySearchableFields = [
        'subject',
        'text'
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
