<?php

namespace Modules\HelpCenter\Models;

use App\Http\Traits\DefaultGlobalScopes;
use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\HelpCenter\Traits\ModelRelations\HelpCenterCategoryRelations;

class HelpCenterCategory extends Model
{
    use DefaultGlobalScopes;
    use HasFactory,SoftDelete;
    use Searchable;
    use Orderable;
    use HelpCenterCategoryRelations;
    protected $table = 'help_center_categories';

    public static function customizedBooted(){}


    protected $fillable=[
        'name',
        'description',
        'image',
        'deleted',
        'deleted_by_cascade',
        'deleted_at'
    ];

    private $mySearchableFields = [
        'name',
        'description',
    ];

    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'UserGuides',

    ];


    //Attributes
    public function getImageAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return null;
    }



    //Scopes

    public function scopeUnitedAdminUserCategory($query,$orderByField=null,$orderType=null)
    {
        return $query->order($orderByField,$orderType)
            ->with('UserGuides');
    }

}
