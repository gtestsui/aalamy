<?php

namespace Modules\HelpCenter\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\HelpCenter\Traits\ModelRelations\HelpCenterUserGuideRelations;

class HelpCenterUserGuide extends Model
{
    use DefaultGlobalScopes;
    use HasFactory,SoftDelete,ModelSharedScopes;
    use Orderable;
    use Searchable;
    use HelpCenterUserGuideRelations;
    protected $table = 'help_center_user_guides';

    public static function customizedBooted(){}


    protected $fillable=[
        'category_id',
        'title',
        'description',
        'user_types',
        'date',
        'deleted',
        'deleted_by_cascade',
        'deleted_at'
    ];

    private $mySearchableFields = [
        'title',
        'description',
        'user_types',
        'date',
    ];


    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'Images',
        'Videos',
    ];

    //Attributes
    public function getUserTypesAttribute($key){
        return json_decode($key);

    }

    /**
     * @param array $value
     * @return json
     */
    public function setUserTypesAttribute($value){

        $this->attributes['user_types'] = json_encode($value);

    }




}
