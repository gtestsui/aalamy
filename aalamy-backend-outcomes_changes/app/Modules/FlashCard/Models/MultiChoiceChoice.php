<?php

namespace Modules\FlashCard\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Traits\ModelRelations\MultiChoiceChoiceRelations;

class MultiChoiceChoice extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use MultiChoiceChoiceRelations;
    use SoftDelete;

    protected $table = 'multi_choice_choices';

    public static function customizedBooted(){}


    protected $fillable=[
        'multi_choice_question_id',
        'card_id',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    //Attributes





    //Scopes

}
