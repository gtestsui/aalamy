<?php

namespace Modules\QuestionBank\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionBank\Traits\ModelRelations\LibraryQuestion\MatchingLeftListRelations;

class LibraryQuestionMatchingLeftList extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use MatchingLeftListRelations;

    protected $table = 'library_question_matching_left_lists';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByIdAsc', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('id', 'asc');

        });
    }


    protected $fillable=[
        'library_question_id',
        'text',
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
        'RightListRecords',

    ];

    //Attributes

    //Scopes

}
