<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Modules\User\Http\DTO\ParentData;
use Illuminate\Http\Request;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class ParentClass extends UserClass
{

    public function getDataFromRequest(Request $request,UserData $userData=null):ParentData
    {
        $data = ParentData::fromRequest($request);
        return $data;
    }

    public function create(ParentData $parentData, UserData $userData):User
    {
        $user = Parent::createUser($userData);

        $parent = ParentModel::create([
            'user_id' => $user->id,
        ]);
        $user->load('Parent');

        return $user;
    }

    public function update(ParentData $parentData,User $user):User
    {
        $user->load('Parent');

        $parent = $user->Parent;
        $parentArrayForUpdate = $parentData->initializeForUpdate($parentData);
        $parent->update($parentArrayForUpdate);
//        $user->refresh();
        return $user;
    }

    public function updateAccountWithPersonalInfo(ParentData $parentData, UserData $userData,User $user):User
    {
        $user = Parent::updateUser($userData,$user);
        $user = $this->update($parentData,$user);
        return  $user;
    }



}
