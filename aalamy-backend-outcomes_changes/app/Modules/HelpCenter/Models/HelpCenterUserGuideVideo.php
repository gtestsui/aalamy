<?php

namespace Modules\HelpCenter\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\HelpCenter\Traits\ModelRelations\HelpCenterUserGuideVideoRelations;

class HelpCenterUserGuideVideo extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use HelpCenterUserGuideVideoRelations;
    protected $table = 'help_center_user_guide_videos';

    public static function customizedBooted(){

        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('normalOrderBy', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('id', 'asc');
        });


    }


    protected $fillable=[
        'user_guide_id',
        'video',

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
    public function getVideoAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return null;
    }

}
