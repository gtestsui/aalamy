<?php

namespace Modules\User\Http\Controllers\Classes\ImportStudentClasses;

use Modules\User\Models\User;

interface FileInterface
{
    /**
     * validation you want to apply on the request
     */
    public function validationRules():array;

    /**
     * @param User $user is the user who trying to import the file
     * @param mixed $id in educatorStudent is the roster id who trying to import inside it
     * @param mixed $id in schoolStudent is the class id who trying to import inside it
     */
    public function import($file,User $user,$id);
}
