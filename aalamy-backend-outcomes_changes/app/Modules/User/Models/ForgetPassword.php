<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\ModelRelations\ForgetPasswordRelations;

class ForgetPassword extends Model
{
    use DefaultGlobalScopes;
    use  HasFactory;
    use  ForgetPasswordRelations;
    use SoftDelete;


    public static function customizedBooted(){}


    protected $fillable = [
        'user_id',
        'code',
        'code_created_at',
        'attempt',

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




}
