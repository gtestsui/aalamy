<?php

namespace Modules\Chat\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Chat\Traits\ModelRelations\ChatMessageRelations;

class ChatMessage extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use SoftDelete;
    use ChatMessageRelations;

    protected $table = 'chat_messages';

    public static function customizedBooted(){}


    protected $fillable=[
        'chat_id',
        'message',
        'from_user_id',
        'to_user_id',
        'deleted_by',//json should contain (value of from_user_id,to_user_id)
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    protected $mySearchableFields = [
    ];

    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [

    ];


    public function Chat(){
        return $this->belongsTo(Chat::class);
    }

    //Attributes

    public function getDeletedByAttribute($key){
        return is_null($key)
            ?[]
            :json_decode($key);

    }

    /**
     * @param array $value
     * @return json
     */
    public function setDeletedByAttribute($value){

        $this->attributes['deleted_by'] = json_encode($value);

    }



    //Scopes
    public function scopeDoesntDeletedByMe($query,$user_id){
        return $query->where(function ($query)use ($user_id){
            return $query->whereNull('deleted_by')
                ->orWhereJsonDoesntContain('deleted_by',$user_id);
        });
    }

}
