<?php

namespace Modules\Outcomes\Http\Controllers\Classes;



use Modules\Outcomes\Models\YearGradesTemplate;

class OutcomesLevelCheckerService
{



    /** @param YearGradesTemplate $objectOfYearTemplate */
    public static function isBelongToFirstOrSecondOrThirdOrFourthLevel($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 1
            || $objectOfYearTemplate->base_level_id == 2
            || $objectOfYearTemplate->base_level_id == 3
            || $objectOfYearTemplate->base_level_id == 4
        ){
            return true;
        }
        return false;
    }

    /**
     * تننتمي الى الصف الاول او الثاني الثانوي العلمي
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public static function isBelongToFirstOrSecondSecondaryScientific($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 10 || $objectOfYearTemplate->base_level_id == 12){
            return true;
        }
        return false;
    }


    /**
     * تننتمي الى الصف الثامن
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public static function isBelongToEightLevel($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 8 ){
            return true;
        }
        return false;
    }

    /**
     * تننتمي الى الصف السابع
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public static function isBelongToSevenLevel($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 7 ){
            return true;
        }
        return false;
    }

    /**
     * تننتمي الى الصف السادس
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public static function isBelongToSixLevel($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 6 ){
            return true;
        }
        return false;
    }

    /**
     * تننتمي الى الصف الخامس
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public static function isBelongToFifthLevel($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 5 ){
            return true;
        }
        return false;
    }

    /**
     * تننتمي الى الصف الاول او الثاني الثانوي الأدبي
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public static function isBelongToFirstOrSecondSecondaryLiterary($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id == 11 || $objectOfYearTemplate->base_level_id == 13){
            return true;
        }
        return false;
    }



}
