<?php


namespace App\Http\Traits;


use App\Scopes\DefaultOrderByScope;

trait DefaultGlobalScopes
{

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        //we have called the customizedBooted() function in the first
        // to make all code in that function work first
        // because the priority its matter here
        // for example if you want to delete global scope(DefaultOrderByScope)
        //if we called the customizedBooted() after call adGlobalScope then
        // delete that scope will not work
        Self::customizedBooted();
        static::addGlobalScope(new DefaultOrderByScope);
    }

    /**
     * if you have some models have shared scopes
     * then override this method and call your global scope
     * like static::addGlobalScope(new DefaultOrderByScope);
     *
     * or use it if you want to apply scope in full model
     */
    protected static function customizedBooted(){

    }

}
