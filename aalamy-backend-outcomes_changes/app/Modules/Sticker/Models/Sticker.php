<?php

namespace Modules\Sticker\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Sticker\Traits\ModelRelations\StickerRelations;

class Sticker extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use StickerRelations;

    protected $table = 'stickers';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'educator_id',
        'teacher_id',
        'name',
        'icon',
        'mark',

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
        'StudentPageStickers',

    ];

    //Attributes
    public function getIconAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        return $key;
    }



    //Scopes


}
