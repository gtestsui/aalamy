<?php

namespace Modules\User\Http\Controllers\Classes\AccountDetails;


interface EducatorAndSchoolDetailsInterface
{
    /**
     * @return array
     */
    public function getDetails();

    /**
     * @return int
     */
    public function myStudentsCount();

    /**
     * @return int
     */
    public function myTeachersCount();

    /**
     * @return int
     */
    public function myStudentParentsCount();

    /**
     * @return int
     */
    public function myAssignmentsCount();

    /**
     * @return int
     */
    public function mySubjectsCount();

    /**
     * @return int
     */
    public function myRostersCount();

    /**
     * @return int
     */
    public function myLevelsCount();

    /**
     * @return int
     */
    public function myClassesCount();

    /**
     * @return int
     */
    public function myQuestionsBankCount();

}
