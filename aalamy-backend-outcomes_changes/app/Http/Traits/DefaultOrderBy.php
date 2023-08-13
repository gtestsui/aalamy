<?php


namespace App\Http\Traits;


use App\Scopes\DefaultOrderByScope;

trait DefaultOrderBy
{

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootDefaultOrderBy()
    {

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
