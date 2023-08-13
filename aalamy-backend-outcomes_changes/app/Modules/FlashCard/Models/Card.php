<?php

namespace Modules\FlashCard\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Traits\ModelRelations\CardRelations;

class Card extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use CardRelations;

    protected $table = 'cards';

    public static function customizedBooted(){}


    protected $fillable=[
        'flash_card_id',
        'question',
        'answer',
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
