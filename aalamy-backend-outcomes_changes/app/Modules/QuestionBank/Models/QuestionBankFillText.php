<?php

namespace Modules\QuestionBank\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionBank\Traits\ModelRelations\QuestionBank\FillTextRelations;

class QuestionBankFillText extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use FillTextRelations;

    protected $table = 'question_bank_fill_texts';

    public static function customizedBooted(){}


    protected $fillable=[
        'question_id',
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


    ];

    //Attributes

//    public function getCorrectTextsAttribute($key){
//        return json_decode($key);
//
//    }
//
//    /**
//     * @param array $value
//     * @return json
//     */
//    public function setCorrectTextsAttribute($value){
//
//        $this->attributes['correct_texts'] = json_encode($value);
//
//    }


    //Scopes

}
