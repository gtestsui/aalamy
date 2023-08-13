<?php


namespace App\Http\Traits;


use App\Exceptions\ErrorMsgException;
use App\Scopes\WithoutDeletedItemsScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Modules\User\Http\Controllers\Classes\UserServices;
use phpDocumentor\Reflection\Types\Boolean;

trait SoftDelete
{

    private $deletedColumn = 'deleted';
    private $deletedByCascade = 'deleted_by_cascade';
    private $deletedAtColumn = 'deleted_at';
    protected static function bootSoftDelete()
    {

        /**
         * WithoutDeletedItemsScope return just un deleted items
         */
        static::addGlobalScope(new WithoutDeletedItemsScope);


        //there is some models not booted so when calling middleware RemoveWithoutDeletedItemsScopeMiddleware
        //the  static::$globalScopes for these models will not have been initialized
        //,and it will be initialized after the request resolved(have logged user object and the route parameters)
        if( static::$withoutDeletedItemsScopeHasRemoved == false
            &&!is_null(request()->route('soft_delete'))
            && UserServices::isSuperAdmin(request()->user())){

            unset(static::$globalScopes[static::class][WithoutDeletedItemsScope::class]);
        }


        static::updated(function($model) {
            if($model->isDeletedAsSoft()){
//                dd('deleted');
                $model->cascadeSoftDelete();
            }

            if($model->isRestored()){
//                dd('restored');
                $model->cascadeRestoreSoftDelete();
            }
        });

    }



    /**
     * notice: the type of the field should be boolean
     * if you want to change the deleted column name in database
     * just override the const DELETED
     * ex : put in your model:
     * const DELETED='your column name'
     */
    public function getDeletedColumn(){
        return defined('static::DELETED')
            ?static::DELETED
            :$this->deletedColumn;
    }

    /**
     * notice: the type of the field should be boolean
     * if you want to change the deleted column name in database
     * just override the const DELETED_BY_CASCADE
     * ex : put in your model:
     * const DELETED_BY_CASCADE='your column name'
     */
    public function getDeletedByCascadeColumn(){
        return defined('static::DELETED_BY_CASCADE')
            ?static::DELETED_BY_CASCADE
            :$this->deletedByCascade;
    }

    /**
     * notice: the type of the field should be DateTime
     * if you want to change the deleted column name in database
     * just override the const DELETED_AT
     * ex : put in your model:
     * const DELETED_AT='your column name'
     */
    public function getDeletedAtColumn(){
        return defined('static::DELETED_AT')
            ?static::DELETED_AT
            :$this->deletedAtColumn;
    }


    //scopes
    /**
     * get the elements have deleted filed true or false dependence on $deleted var
     * @var $deleted Boolean
     * @return  Builder
     */
    public function scopeDeletedAsSoft($query,$deleted=true){

        return $query->where($this->getDeletedColumn(),$deleted);

    }

    /**
     * use this method to delete before retrieve from database
     * @param bool $cascade if this item deleted by cascade so will be true
     */
//    public function scopeSoftDelete($query,$cascade=false){
//
//        return $query->update([
//            $this->getDeletedColumn() => true,
//            $this->getDeletedByCascadeColumn() => $cascade,
//            $this->getDeletedAtColumn() => Carbon::now(),
//        ]);
//    }

    /**
     * use this method to delete a single record that retrieved from database
     */
    public function softDeleteObject($cascade=false){

        return $this->update([
            $this->getDeletedColumn() => true,

//            $this->getDeletedByCascadeColumn() => $cascade,
            $this->getDeletedByCascadeColumn() => $cascade
                ?$this->{$this->getDeletedByCascadeColumn()}+1
                :0,

            $this->getDeletedAtColumn() => $this->{$this->getDeletedColumn()}
                ?$this->{$this->getDeletedAtColumn()}
                :Carbon::now(),//check if it's deleted as cascade before so save the first date its delete by it
        ]);
    }

//    /**
//     * @param bool $withCascade if its true then restore all the records deleted by cascade
//     */
//    public function scopeRestoreSoftDelete($query,$withCascade=false){
//        return $query->when($withCascade,function ($q){
//            return $q->withDeletedItems()
////                ->where($this->getDeletedByCascadeColumn(),true);
//                ->where($this->getDeletedByCascadeColumn(),'>',0);
//        })
//        ->update([
//            $this->getDeletedColumn() => false,
////            $this->getDeletedByCascadeColumn() => false,
//            $this->getDeletedByCascadeColumn() => $withCascade?$this->{$this->getDeletedByCascadeColumn()}-1:0,
//            $this->getDeletedAtColumn() => null,
//        ]);
//    }

    public function restoreSoftDeleteObject($withCascade=false){
        /**
         * @var int $deletedByCascadeNewCount the count of items that delete this object as cascade
         * (so each time child delete his parent we decrease it 1 until become its value equal to 0)
         * and if it's value equal to 0 that mean there is no child delete it as cascade
         * , so we can restore it
         */
        $deletedByCascadeNewCount = $this->{$this->getDeletedByCascadeColumn()}-1;
        return $this/*->when($withCascade,function ($q){
            return $q->withDeletedItems()
                ->where($this->getDeletedByCascadeColumn(),true);
        })*/
        ->update([
            $this->getDeletedColumn() => $deletedByCascadeNewCount==0?false:true,
//            $this->getDeletedByCascadeColumn() => false,
            $this->getDeletedByCascadeColumn() => $withCascade?$deletedByCascadeNewCount:0,
            $this->getDeletedAtColumn() => $deletedByCascadeNewCount==0?null:$this->{$this->getDeletedAtColumn()},
        ]);
    }

    //functions
    public function softDeleteOrRestore(){

        //check if I'm trying to restore
        if($this->checkTryingToRestore()){
            $this->checkCanRestore();
        }
        return $this->update([
            $this->getDeletedColumn() => $this->oppositeDeletedState(
                $this->{$this->getDeletedColumn()}
            ),
//            $this->getDeletedByCascadeColumn() => false,
            $this->getDeletedByCascadeColumn() => 0,
            $this->getDeletedAtColumn() => $this->oppositeDeletedAtState(
                $this->{$this->getDeletedColumn()}
            ),
        ]);

    }

    /**
     * if deleted column is true that mean im trying to restore the record
     * else im trying to delete the record
     * @return bool
     */
    public function checkTryingToRestore(){
        return (bool)$this->{$this->getDeletedColumn()};
    }

    /**
     * check if the record can be restored or not
     */
    public function checkCanRestore(){
        //check if the element has been deleted by cascade
//        if($this->{$this->getDeletedByCascadeColumn()})
        if($this->{$this->getDeletedByCascadeColumn()} > 0)
            throw new ErrorMsgException('you cant restore deleted item because the base parent is deleted too');

//        return $this->checkNoRequiredParentsAreDeleted();//The previous condition is enough to check for this
    }

    public function checkNoRequiredParentsAreDeleted(){
        //check if the record doesn't have any deleted required parent
        $parentRelations = $this->getParentRelations();
        foreach ($parentRelations as $parentRelationName) {
            if(!method_exists($this,$parentRelationName))
                throw new ErrorMsgException('invalid relation name');
            $relationObject = $this->$parentRelationName()->get();

            if(count($relationObject)<=0)
                throw new ErrorMsgException('you cant restore deleted item because the base parent is deleted too');

        }
        return true;
    }

    /**
     * @var string[] $parentRelations
     * when the model belongs to another  parent model
     * and the model and his parent are deleted
     * andddd I can't restore the model if the parent is deleted
     * then I should fill $parentRelations array by
     * the relation name to that parent model
     * to prevent restore that model
     */
    public function getParentRelations(){
        return $this->parentRelations??[];
    }


    /**
     * if current state is true return false
     * else return true
     */
    public function oppositeDeletedState(bool $currenDeletedState){
        return !$currenDeletedState;
    }

    /**
     * if the current state is true the im make restore so the deleted_at should be null
     * else i make soft delete so the deleted_at should be current dateTime
     */
    public function oppositeDeletedAtState(bool $currenDeletedState){
        return $currenDeletedState?null:Carbon::now();
    }

    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    public function getRelationsSoftDelete(){
        return $this->relationsSoftDelete??[];
    }


    public function isDeletedAsSoft(){
//        if($this->wasChanged($this->getDeletedColumn()) && $this->delete == true)
//            dd('$this');
//        dd($this);

        if($this->wasChanged($this->getDeletedColumn())
            && $this->{$this->getDeletedColumn()} == true
            && $this->getOriginal($this->getDeletedColumn()) == false)
            return true;
        return false;

//        if($this->wasChanged($this->getDeletedColumn()) && $this->{$this->getDeletedColumn()} == true)
//            return true;
//        return false;
    }

    public function isRestored(){

        if($this->wasChanged($this->getDeletedColumn())
            && $this->{$this->getDeletedColumn()} == false
            && $this->getOriginal($this->getDeletedColumn()) == true)
            return true;
        return false;

//        if($this->wasChanged($this->getDeletedColumn()) && $this->{$this->getDeletedColumn()} == false)
//        if(isset($this->level_subject_id))
//        dd($this);


//        if(($this->wasChanged($this->getDeletedColumn())
//                && $this->{$this->getDeletedColumn()} == false
//            )
//            || ($this->wasChanged($this->getDeletedByCascadeColumn())
//                && $this->{$this->getDeletedByCascadeColumn()} == $this->getOriginal($this->getDeletedByCascadeColumn()) - 1
//            ))
//            return true;
//        return false;
    }

    public function cascadeSoftDelete(){
        $relationsSoftDelete = $this->getRelationsSoftDelete();

        foreach ($relationsSoftDelete as $relationName) {
//            echo$relationName.' ';
            if(!method_exists($this,$relationName))
                throw new ErrorMsgException('invalid relation name');
//            $this->$relationName()->softDelete(true);
            $this->$relationName()->withDeletedItems()->get()//we have used withDeletedItems because we want to increase the deleted_by_cascade value in the deleted items too
            ->each(function (Model $model) {
                $model->softDeleteObject(true);
            });

        }
    }

    public function cascadeRestoreSoftDelete(){
        $relationsSoftDelete = $this->getRelationsSoftDelete();
//        echo 'restore/ ';
        foreach ($relationsSoftDelete as $relationName) {
//            echo$relationName.' ';
            if(!method_exists($this,$relationName))
                throw new ErrorMsgException('invalid relation name');
//            $this->$relationName()->restoreSoftDelete(true);
            $this->$relationName()->withDeletedItems()
//                ->where($this->getDeletedByCascadeColumn(),true)
                ->where($this->getDeletedByCascadeColumn(),'>',0)
                ->get()->each(function (Model $model) {
                $model->restoreSoftDeleteObject(true);
            });
        }
    }


    /**
     * return all the items even deleted or not
     */
    public function scopeWithDeletedItems($query){
        return $query->withoutGlobalScope(WithoutDeletedItemsScope::class);
    }

    /**
     * return just deleted items
     */
    public function scopeOnlyDeletedItems($query){
        return $query->withDeletedItems()
            ->deletedAsSoft(true);
    }

    /**
     * @param mixed $softDelete
     * if the $softDelete is null then return items without deleted items
     * (because by default scope WithoutDeletedItemsScope will return the un deleted items )
     * if not null the return just deleted items
     */
    public function scopeTrashed($query,$softDelete=null){
        return isset($softDelete)
            ?$query->onlyDeletedItems()
            :$query;
    }


    public static function removeWithoutDeletedItemsScope(){
        unset(static::$globalScopes[static::class][WithoutDeletedItemsScope::class]);

    }

    public static $withoutDeletedItemsScopeHasRemoved = false;

    public static function removeWithoutDeletedItemsScopeFromAllModel(){
        foreach (Model::$globalScopes as $modelPath => $globalScopes){
            $modelPath::$withoutDeletedItemsScopeHasRemoved = true;
                unset(Model::$globalScopes[$modelPath][WithoutDeletedItemsScope::class]);

        }


    }

//    protected function initializeSoftDelete()
//    {
////        dump(static::class);
//        // Automatically create a random token
//    }



}
