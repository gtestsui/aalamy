<?php

namespace Modules\Chat\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Chat\Traits\ModelRelations\ChatRelations;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\User;

class Chat extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use SoftDelete;
    use ChatRelations;

    protected $table = 'chats';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByUpdatedAt', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('updated_at', 'desc');

        });

    }


    //deleted_by when user (parent,school,..) delete the chat from his chat list ,so we insert his account_type in same record(to ensure the chat its will not displayed for him just)
    protected $fillable=[
        'first_user_id',
        'second_user_id',
        'deleted_by',//json should contain (value of first_user_id,second_user_id)
    	'unread_message_count_from_first',
        'unread_message_count_from_second',
    	'it_seen_from_first',
        'it_seen_from_second',
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


    public function Messages(){
        return $this->hasMany(ChatMessage::class);
    }

    public function LastMessage(){
        return $this->hasOne(ChatMessage::class);
    }

    public function FirstUser(){
        return $this->belongsTo(User::class);
    }

    public function SecondUser(){
        return $this->belongsTo(User::class);
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
