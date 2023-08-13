<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\User\Traits\ModelRelations\UserRelations;

class User extends Authenticatable
{
    use DefaultGlobalScopes;
    use HasApiTokens,HasFactory,Notifiable,SoftDelete,ModelSharedScopes;
    use Searchable;
    use Orderable;
    use SoftDelete;
    use UserRelations;


    public static function customizedBooted(){}


    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
        'address_id',
        'verified_status',
        'image',
        'account_type',
        'phone_code',
        'phone_iso_code',
        'phone_number',
        'gender',
        'date_of_birth',
        'account_id',
        'login_service_id',
        'unique_username',


        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'verified_code',
        'verified_code_created_at',
//        'login_service_id',
        'Topics',
        'Subjects',
        'Lessons',
        'Units',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'Student',
        'Parent',
        'Educator',
        'School',
        'LoggedDevices',
        'ForgetPassword',
        'Teachers',
//        'Address',
        'AccountConfirmationSetting',
        'DiscussionCornerPosts',
        'DiscussionCornerSurveys',
        'SurveyUsers',
        'Firebase',
        'Lessons',
        'Units',
        'Levels',
        'Achievements',
        'Subjects',
        'UserSubscription',
        'ContactUS',
        'Topics',

    ];

    private $mySearchableFields = [
        'fname',
        'lname',
        'email',
        'phone_code',
        'phone_number',
        'gender',
        'date_of_birth',
    ];

    private function getMySearchExpressions(){
        return [
            \DB::raw('CONCAT(phone_code," ",phone_number)'),
            \DB::raw('CONCAT(fname," ",lname)')
        ];
    }


    public function getSearchableFields(){
        return array_merge($this->mySearchableFields,
            $this->getMySearchExpressions());
    }


    /**
     * if the image path contain http that mean this image stored
     * from outer service like google,facebook...
     */
    public function getImageAttribute($key){
        if(is_null($key))
            return defaultUserImage($this->account_type);
        elseif(str_contains($key,'http'))
            return $key;
        return baseRoute().$key;
    }

    public function setPasswordAttribute($key){
        $this->attributes['password'] =
                is_null($key)
                ?$key
                :bcrypt($key);

    }

    public function setEmailAttribute($key){
        $this->attributes['email'] =
            is_null($key)
                ?$key
                :strtolower($key);

    }

    public function getFullName($separator=null){
        return isset($separator)
            ?$this->fname.$separator.$this->lname
            :$this->fname.' '.$this->lname;
    }



    //Scopes
    public function scopeRegisteredBy($query, $by='email'){
        return $query->where('account_id',$by);
    }

    public function scopeSuperAdmin($query){
        return $query->where('account_type',config('User.panel.all_account_types.superAdmin'));
    }


}
