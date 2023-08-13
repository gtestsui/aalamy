<?php


namespace Modules\Outcomes\Http\Controllers\Classes;


use Modules\Outcomes\Models\YearGradesTemplate;

class YearGradeServices
{


    /** Collection of YearGradesTemplate */
    public static function proccess( $yearTemplate){
        $result = [];
        $row = [];
        $grandTotalSemestr1 = 0;
        $finalTotalSemestr1 = 0;
        $grandTotalSemestr2 = 0;
        $finalTotalSemestr2 = 0;
        $grandTotalObject = $yearTemplate->where('its_grand_total',true)->first();
        foreach ($yearTemplate as $objectOfYearTemplate){
            if(isset($objectOfYearTemplate->writable_subject_name)){
                $row['name'] = $objectOfYearTemplate->writable_subject_name;
                $row['max_degree'] = $objectOfYearTemplate->max_degree;
                $row["total_semester_1"] = 0;//this should come from database
                $row["total_semester_2"] = 0;//this should come from database
                if($grandTotalObject->is_grand_total){
                    $row["total_semester_1"] = $grandTotalSemestr1;
                    $row["total_semester_2"] = $grandTotalSemestr2;

                }
                if($grandTotalObject->is_final_total){
                    $row["total_semester_1"] = $finalTotalSemestr1;
                    $row["total_semester_2"] = $finalTotalSemestr2;

                }
                $row['total'] = $row["total_semester_1"] + $row["total_semester_2"];
                $row['final_degree'] = $row['total']/2;
                
                //null => There are classes doesn't have grandTotal , second condition because the subjects after grandTotal it's should not included
                if(!is_null($grandTotalObject) && $grandTotalObject->order > $objectOfYearTemplate->order){
                    $grandTotalSemestr1 += $row["total_semester_1"];
                    $grandTotalSemestr2 += $row["total_semester_2"];
                }
                if($grandTotalObject->order != $objectOfYearTemplate->order){
                    $finalTotalSemestr1 += $finalTotalSemestr1;
                    $finalTotalSemestr2 += $finalTotalSemestr2;
                }


            }else{
                $row['name'] = $objectOfYearTemplate->BaseSubject->name;
                $row['max_degree'] = $objectOfYearTemplate->BaseSubject
                    ->BaseLevelSubjects[0]
                    ->Rule->max_degree;


                foreach ($objectOfYearTemplate->Marks as $mark){
                    $row["work_degree_semester_{$mark->Subject->semester}"] =
                        $mark->verbal + $mark->jobs_and_worksheets + $mark->activities_and_Initiatives + $mark->quiz;
                    $row["exam_degree_semester_{$mark->Subject->semester}"] =
                        $mark->final_mark;
                    $row["total_semester_{$mark->Subject->semester}"] =
                        $row["work_degree_semester_{$mark->Subject->semester}"] +
                        $row["exam_degree_semester_{$mark->Subject->semester}"];
                }
                $grandTotalSemestr1 += $row["total_semester_1"];
                $finalTotalSemestr1 += $row["total_semester_1"];
                $row['total'] = $row["total_semester_1"] + $row["total_semester_2"];
                $row['final_degree'] = $row['total']/2;

            }

            $result[] = $row;

        }
        return $result;
    }


}
