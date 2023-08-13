<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HasDeletedUserAsSoftScope implements Scope
{


    private $status;
    public function __construct($status=false)
    {
        $this->status = $status;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        //by default will execute user where deleted = false
        //because we applied global scope on user
        $builder->whereHas('User'/*,function ($query){
            return $query->deletedAsSoft(false);
        }*/);
    }

}
