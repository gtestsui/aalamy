<?php

namespace Modules\QuestionBank\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionBank\Traits\ModelRelations\QuestionBank\FillInBlankRelations;

class QuestionBankFillInBlank extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use FillInBlankRelations;

    protected $table = 'question_bank_fill_in_blanks';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByBlankOrdering', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('order', 'asc');

        });
    }


    protected $fillable=[
        'question_id',
        'word',
        'order',
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


//    public function getCorrectWordsAttribute($key){
//        return json_decode($key);
//
//    }
//
//    /**
//     * @param array $value
//     * @return json
//     */
//    public function setCorrectWordsAttribute($value){
//
//        $this->attributes['correct_words'] = json_encode($value);
//
//    }


    //Scopes

}
