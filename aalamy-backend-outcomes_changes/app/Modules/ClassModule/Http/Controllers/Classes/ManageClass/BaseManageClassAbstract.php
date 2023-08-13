<?php


namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;


use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassModel;


abstract class BaseManageClassAbstract
{

    protected abstract function myClassesQuery();

    public function myClasses(): ?Collection
    {
//        $myClassesQuery = $this->myClassesQuery();
        $myClasses = $this->myClassesQuery()->get();

        return $myClasses;
    }

    public function myClassesWithInfo(): ?Collection
    {
        $myClassesQuery = $this->myClassesQuery();
        $myClasses = $myClassesQuery
            ->with(['Level.User'=>function($q){
                return $q->with(['School','Educator']);
            }])
            ->get();
        return $myClasses;
    }


    public function myClassesById($id):?ClassModel
    {
        $myClassesQuery = $this->myClassesQuery();
        $myClasse = $myClassesQuery
            ->where('id',$id)
            ->first();
        return $myClasse;
    }

    public function myClassesByIdOrFail($id):ClassModel
    {
        $myClass = $this->myClassesById($id);
        if(is_null($myClass))
            throw new ErrorMsgException('this class doesnt belongs to you');

        return $myClass;
    }


}
