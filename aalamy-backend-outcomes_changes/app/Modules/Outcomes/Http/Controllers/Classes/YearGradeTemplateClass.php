<?php


namespace Modules\Outcomes\Http\Controllers\Classes;


use Illuminate\Database\Eloquent\Collection;
use Modules\Outcomes\Models\Mark;
use Modules\Outcomes\Models\YearGradesTemplate;

class YearGradeTemplateClass
{

    /** Collection of YearGradesTemplate */
    protected  $yearTemplate;
    protected  $grandTotalSemester1 = null;
    protected  $finalTotalSemester1 = null;
    protected  $grandTotalSemester2 = null;
    protected  $finalTotalSemester2 = null;
    /** @var bool Describes whether the student has succeeded or not */
    protected  $isFailure = false;
    /** @var YearGradesTemplate|null */
    protected  $grandTotalObject;
    protected  $yearGradeGeneralInfo;


    /**
     * @param Collection<YearGradesTemplate>
     */
    public function __construct($yearTemplate,$yearGradeGeneralInfo){
        $this->yearTemplate = $yearTemplate;
        $this->yearGradeGeneralInfo = $yearGradeGeneralInfo;
        $this->grandTotalObject = $yearTemplate->where('its_grand_total',true)->first();
    }

    /**
     * @return bool
     */
    public function getIsFailure(){
        return $this->isFailure;
    }

    /**
     * $failureCount
     * متحول انتيجر يعبر عن عدد المرات التي يمكن للطالب ان يحصل على علامة اقل من النسبة المستحقة اذا حقق نسبة دوام معينة
     * يجب مراجعة شروط النجاح في الملفات
     *
     *
     * @param null $studentStudyingInformation
     * @return array
     */
    public function process($studentStudyingInformation=null){
        //array of $row
        $result = [];
        //$row is the record of outcomes
        $row = [];

        $failureCount = 0;

        $oralSkillsSubjectPercentage = null;
        $writtenSkillsSubjectPercentage = null;
        $totalOfOralAndWritten = null;
        foreach ($this->yearTemplate as $objectOfYearTemplate){
            $row['year_grade_template_id'] = $objectOfYearTemplate->id;
            //order -1 that mean it's not included in the template so ignore it
            if($objectOfYearTemplate->order == -1){
                continue;
            }
            $this->processBySubjectType($objectOfYearTemplate,$row);

            if(OutcomesLevelCheckerService::isBelongToFirstOrSecondOrThirdOrFourthLevel($objectOfYearTemplate)){
                $row["total_semester_1"] = $this->changeMarkToWrittenEstimate($row["total_semester_1"]);
                $row["total_semester_2"] = $this->changeMarkToWrittenEstimate($row["total_semester_2"]);
                $row['final_degree'] = $this->changeMarkToWrittenEstimate($row['final_degree']);
            }

            if(OutcomesLevelCheckerService::isBelongToFirstOrSecondSecondaryLiterary($objectOfYearTemplate) || OutcomesLevelCheckerService::isBelongToFirstOrSecondSecondaryScientific($objectOfYearTemplate)){
                if(!is_null($row['final_degree'])){

                    if($this->itsArabicSubject($objectOfYearTemplate)){
                        if(OutcomesLevelCheckerService::isBelongToFirstOrSecondSecondaryLiterary($objectOfYearTemplate)){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,50)){
                                $this->isFailure = true;
                            }
                        }elseif(OutcomesLevelCheckerService::isBelongToFirstOrSecondSecondaryScientific($objectOfYearTemplate)){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,40)){
                                $this->isFailure = true;
                            }
                        }

                    }
                    else{
                        if($this->checkItsBehaviorSubject($objectOfYearTemplate)){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,70)){
                                $this->isFailure = true;
                            }
                        }
                        if($objectOfYearTemplate->its_grand_total){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,50)){
                                $this->isFailure = true;
                            }
                        }
                        if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,20)){
                            $this->isFailure = true;
                        }
                    }

                }
            }

            //the behavior subject condition is belongs to eight and seven
            if(OutcomesLevelCheckerService::isBelongToEightLevel($objectOfYearTemplate) || OutcomesLevelCheckerService::isBelongToSevenLevel($objectOfYearTemplate) || OutcomesLevelCheckerService::isBelongToSixLevel($objectOfYearTemplate) || OutcomesLevelCheckerService::isBelongToFifthLevel($objectOfYearTemplate)){
                $actualAttendeeHours = (int)$this->yearGradeGeneralInfo->actual_attendee_hours_semester_1 + (int)$this->yearGradeGeneralInfo->actual_attendee_hours_semester_1;
                $requiredActualAttendeeHours = round($actualAttendeeHours * (75/100));
                $totalExcusedAbsence = $this->yearGradeGeneralInfo->excused_absence_semester_1 +  $this->yearGradeGeneralInfo->excused_absence_semester_2;
                $totalUnExcusedAbsence = $this->yearGradeGeneralInfo->unexcused_absence_semester_1 +  $this->yearGradeGeneralInfo->unexcused_absence_semester_2;
                $studentAttendee = $actualAttendeeHours - ($totalExcusedAbsence + $totalUnExcusedAbsence);
                $haveAttendeePercentage = $studentAttendee >= $requiredActualAttendeeHours;
                if(!is_null($row['final_degree'])){

                    if($this->itsArabicSubject($objectOfYearTemplate)){
                        if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,50)){
                            $this->isFailure = true;
                        }
                    }
                    elseif($this->itsOralSkillsSubject($objectOfYearTemplate)){
                        $oralSkillsSubjectPercentage += $row['final_degree'];
                        $totalOfOralAndWritten += $oralSkillsSubjectPercentage;
                    }
                    elseif($this->itsWrittenSkillsSubject($objectOfYearTemplate)){
                        $writtenSkillsSubjectPercentage += $row['final_degree'];
                        $totalOfOralAndWritten += $oralSkillsSubjectPercentage;
                        if(!is_null($oralSkillsSubjectPercentage)){
                            $maxDegree = 200;
                            $failurePercentageMark = $maxDegree * (50/100);
                            if($totalOfOralAndWritten < $failurePercentageMark){
                                $this->isFailure = true;
                            }
                        }
                    }
                    else{
                        if($this->checkItsBehaviorSubject($objectOfYearTemplate) && (OutcomesLevelCheckerService::isBelongToEightLevel($objectOfYearTemplate) || OutcomesLevelCheckerService::isBelongToSevenLevel($objectOfYearTemplate))){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,60)){
                                if($haveAttendeePercentage && $failureCount<2){
                                    $failureCount++;
                                }else{
                                    $this->isFailure = true;
                                }

                            }
                        }
                        elseif($objectOfYearTemplate->its_grand_total){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,50)){
                                $this->isFailure = true;
                            }
                        }
                        elseif(!$objectOfYearTemplate->its_final_total){
                            if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,50)){
                                if($haveAttendeePercentage && $failureCount<2){
                                    $failureCount++;
                                }else{
                                    $this->isFailure = true;
                                }

                            }
                        }
                    }

                }
            }

            if(OutcomesLevelCheckerService::isBelongToFirstOrSecondOrThirdOrFourthLevel($objectOfYearTemplate)){
                if($this->itsOralSkillsSubject($objectOfYearTemplate) || $this->itsWrittenSkillsSubject($objectOfYearTemplate) || $this->checkItsMathSubject($objectOfYearTemplate)){
                    if($this->checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,40)){
                        $this->isFailure = true;
                    }
                }
            }

            $result[] = $row;
            $row = [];

        }
        return $result;
    }

    /**
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    private function processBySubjectType($objectOfYearTemplate,&$row){
        if(isset($objectOfYearTemplate->writable_subject_name)){
            $this->processWritableSubject($objectOfYearTemplate,$row);
        }else{
            $this->processStoredSubject($objectOfYearTemplate,$row);
        }
    }

    /**
     * name is the subject name
     * max_degree is the max degree of the subject
     * failure_point is the grade that a student must exceed in order to pass their subject
     * is_editable this for the front to let them edit the mark for a record
     *
     *
     * @param YearGradesTemplate $objectOfYearTemplate
     */
    public function processWritableSubject($objectOfYearTemplate,&$row){

        $row['name'] = $objectOfYearTemplate->writable_subject_name;
        $row['max_degree'] = $objectOfYearTemplate->max_degree;
        $row['failure_point'] = $objectOfYearTemplate->failure_point;

        $row["work_degree_semester_1"] =isset($objectOfYearTemplate->YearGradeData) && isset($objectOfYearTemplate->YearGradeData->work_degree_semester_1)
            ?$objectOfYearTemplate->YearGradeData->work_degree_semester_1
            :null;
        $row["work_degree_semester_2"] =isset($objectOfYearTemplate->YearGradeData) && isset($objectOfYearTemplate->YearGradeData->work_degree_semester_2)
            ?$objectOfYearTemplate->YearGradeData->work_degree_semester_2
            :null;

        $row["exam_degree_semester_1"] =isset($objectOfYearTemplate->YearGradeData) && isset($objectOfYearTemplate->YearGradeData->exam_degree_semester_1)
            ?$objectOfYearTemplate->YearGradeData->exam_degree_semester_1
            :null;
        $row["exam_degree_semester_2"] =isset($objectOfYearTemplate->YearGradeData) && isset($objectOfYearTemplate->YearGradeData->exam_degree_semester_2)
            ?$objectOfYearTemplate->YearGradeData->exam_degree_semester_2
            :null;

        $row["total_semester_1"] = isset($objectOfYearTemplate->YearGradeData) && isset($objectOfYearTemplate->YearGradeData->total_semester_1)
            ?$objectOfYearTemplate->YearGradeData->total_semester_1
            :null;
        $row["total_semester_2"] = isset($objectOfYearTemplate->YearGradeData) && isset($objectOfYearTemplate->YearGradeData->total_semester_2)
            ?$objectOfYearTemplate->YearGradeData->total_semester_2
            :null;
        if($objectOfYearTemplate->its_grand_total){
            $row["total_semester_1"] = $this->grandTotalSemester1;
            $row["total_semester_2"] = $this->grandTotalSemester2;

        }
        if($objectOfYearTemplate->its_final_total){
            $row["total_semester_1"] = $this->finalTotalSemester1;
            $row["total_semester_2"] = $this->finalTotalSemester2;

        }
        if(is_null($row["total_semester_1"]) && is_null($row["total_semester_2"])){
            $row['total'] = null;
        }else{
            $row['total'] = $row["total_semester_1"] + $row["total_semester_2"];
        }

        //اذا كان هناك علامة للفصل الاول فقط او الفصل الثاني فقط فالمحصلة النهائية يجب ان تكون هي نفس علامة الفصل
        if(is_null($row["total_semester_1"]) || is_null($row["total_semester_2"])){
            $row['final_degree'] =round($row['total']);
        }else{
            $row['final_degree'] = is_null($row['total'])?null:round($row['total']/2);
        }
//        $row['final_degree'] = is_null($row['total'])?null:$row['total']/2;

        $row['is_editable'] = false;
        if(!$objectOfYearTemplate->its_grand_total && !$objectOfYearTemplate->its_final_total){
            $row['is_editable'] = /*true*/false;//we dont need to make them editable in the outcome
            //just the subjects which have order before grand_total should enter the sum of grandTotal
            if($this->shouldEnterTheGrandTotal($objectOfYearTemplate)){
                if(!is_null($row["total_semester_1"])){
                    $this->grandTotalSemester1 += $row["total_semester_1"];
                }
                if(!is_null($row["total_semester_2"])){
                    $this->grandTotalSemester2 += $row["total_semester_2"];
                }
            }
            //all subjects should enter the finalTotal sum
            if(!is_null($row["total_semester_1"])){
                $this->finalTotalSemester1 += $row["total_semester_1"];
            }
            if(!is_null($row["total_semester_2"])){
                $this->finalTotalSemester2 += $row["total_semester_2"];
            }
        }

    }

    /** @return bool */
    public function shouldEnterTheGrandTotal($objectOfYearTemplate)
    {
        //null => There are classes doesn't have grandTotal , second condition because the subjects after grandTotal it's should not included
        if (!is_null($this->grandTotalObject) && $this->grandTotalObject->order > $objectOfYearTemplate->order) {
            return true;
        }
        return false;
    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    public function processStoredSubject($objectOfYearTemplate,&$row){
        $row['is_editable'] = false;
        $row['name'] = $this->getStoredSubjectName($objectOfYearTemplate);
        $row['max_degree'] = $objectOfYearTemplate->BaseSubject
            ->BaseLevelSubjects[0]
            ->Rule->max_degree;
        $row['failure_point'] = $objectOfYearTemplate->BaseSubject
            ->BaseLevelSubjects[0]
            ->Rule->failure_point;

        //مادة علوم عامة
        if($this->itsGeneralScienceSubject($objectOfYearTemplate)){

            $this->processGeneralScience($objectOfYearTemplate,$row);
        //مادة لغة روسية او فرنسية
        }elseif ($this->itsFrenchOrRussianSubject($objectOfYearTemplate)) {

            $this->processFrenchAndRussian($objectOfYearTemplate,$row);

        }else{

            $this->processStoredSubjectMarks($objectOfYearTemplate->Marks,$row);

        }

        if(!is_null($row["total_semester_1"])){
            $this->grandTotalSemester1 += $row["total_semester_1"];
        }

        if(!is_null($row["total_semester_2"])){
            $this->grandTotalSemester2 += $row["total_semester_2"];
        }
        if(!is_null($row["total_semester_1"])){
            $this->finalTotalSemester1 += $row["total_semester_1"];
        }
        if(!is_null($row["total_semester_2"])){
            $this->finalTotalSemester2 += $row["total_semester_2"];
        }
        if(is_null($row["total_semester_1"]) && is_null($row["total_semester_2"])){
            $row['total'] = null;
        }else{
            $row['total'] = $row["total_semester_1"] + $row["total_semester_2"];
        }

        //اذا كان هناك علامة للفصل الاول فقط او الفصل الثاني فقط فالمحصلة النهائية يجب ان تكون هي نفس علامة الفصل
        if(is_null($row["total_semester_1"]) || is_null($row["total_semester_2"])){
            $row['final_degree'] =round($row['total']);
        }else{
            $row['final_degree'] = is_null($row['total'])?null:round($row['total']/2);
        }
//        $row['final_degree'] = is_null($row['total'])?null:$row['total']/2;

    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function getStoredSubjectName($objectOfYearTemplate){

        if($this->itsFrenchOrRussianSubject($objectOfYearTemplate)){
            return 'اللغة الفرنسية/اللغة الروسية';
        }
        if($this->itsGeneralScienceSubject($objectOfYearTemplate)){
            return 'العلوم العامة';
        }
        return $objectOfYearTemplate->BaseSubject->name;

    }


    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function processGeneralScience($objectOfYearTemplate,&$row){
        $row['max_degree'] = 400;
        $physicsAndChemistryMarks = Mark::with('Subject')
            ->where('student_studying_information_id',$objectOfYearTemplate->Marks[0]->student_studying_information_id)
            ->whereHas('Subject',function($query){
                return $query->where('code','فيزياء وكيمياء');
            })
            ->get();

        $biologyAndEarthMarks = Mark::with('Subject')
            ->where('student_studying_information_id',$objectOfYearTemplate->Marks[0]->student_studying_information_id)
            ->whereHas('Subject',function($query){
                return $query->where('code','علم احياء');
            })
            ->get();

        $physicsAndChemistryExamDegreeSemester1 = null;
        $physicsAndChemistryExamDegreeSemester2 = null;
        $physicsAndChemistryWorkDegreeSemester1 = null;
        $physicsAndChemistryWorkDegreeSemester2 = null;
        $physicsAndChemistryTotalSemester1 = null;
        $physicsAndChemistryTotalSemester2 = null;

        $biologyAndEarthExamDegreeSemester1 = null;
        $biologyAndEarthExamDegreeSemester2 = null;
        $biologyAndEarthWorkDegreeSemester1 = null;
        $biologyAndEarthWorkDegreeSemester2 = null;
        $biologyAndEarthTotalSemester1 = null;
        $biologyAndEarthTotalSemester2 = null;
        foreach ($physicsAndChemistryMarks as $physicsAndChemistryMark){
            if(!is_null($physicsAndChemistryMark->verbal)
                && !is_null($physicsAndChemistryMark->jobs_and_worksheets)
                && !is_null($physicsAndChemistryMark->activities_and_Initiatives)
                && !is_null($physicsAndChemistryMark->quiz)
            ){
                ${"physicsAndChemistryWorkDegreeSemester".$physicsAndChemistryMark->Subject->semester} =
                    $physicsAndChemistryMark->verbal + $physicsAndChemistryMark->jobs_and_worksheets + $physicsAndChemistryMark->activities_and_Initiatives + $physicsAndChemistryMark->quiz;

            }

            if(!is_null($physicsAndChemistryMark->exam)){
                ${"physicsAndChemistryExamDegreeSemester".$physicsAndChemistryMark->Subject->semester} =
                    $physicsAndChemistryMark->exam;
            }

            if(
                !is_null(${"physicsAndChemistryWorkDegreeSemester".$physicsAndChemistryMark->Subject->semester})
                && !is_null(${"physicsAndChemistryExamDegreeSemester".$physicsAndChemistryMark->Subject->semester})
            ){
                ${"physicsAndChemistryTotalSemester".$physicsAndChemistryMark->Subject->semester} =
                    ${"physicsAndChemistryWorkDegreeSemester".$physicsAndChemistryMark->Subject->semester} +
                    ${"physicsAndChemistryExamDegreeSemester".$physicsAndChemistryMark->Subject->semester};
            }

        }

        foreach ($biologyAndEarthMarks as $biologyAndEarthMark){

            if(
                !is_null($biologyAndEarthMark->verbal)
                && !is_null($biologyAndEarthMark->jobs_and_worksheets)
                && !is_null($biologyAndEarthMark->activities_and_Initiatives)
                && !is_null($biologyAndEarthMark->quiz)
            ){
                ${"biologyAndEarthWorkDegreeSemester".$biologyAndEarthMark->Subject->semester} =
                    $biologyAndEarthMark->verbal + $biologyAndEarthMark->jobs_and_worksheets + $biologyAndEarthMark->activities_and_Initiatives + $biologyAndEarthMark->quiz;

            }

            if(!is_null($biologyAndEarthMark->exam)){
                ${"biologyAndEarthExamDegreeSemester".$biologyAndEarthMark->Subject->semester} =
                    $biologyAndEarthMark->exam;
            }


            if(
                !is_null(${"biologyAndEarthWorkDegreeSemester".$biologyAndEarthMark->Subject->semester})
                && !is_null(${"biologyAndEarthExamDegreeSemester".$biologyAndEarthMark->Subject->semester})
            ){
                ${"biologyAndEarthTotalSemester".$biologyAndEarthMark->Subject->semester} =
                    ${"biologyAndEarthWorkDegreeSemester".$biologyAndEarthMark->Subject->semester} +
                    ${"biologyAndEarthExamDegreeSemester".$biologyAndEarthMark->Subject->semester};
            }


        }
        if(is_null($physicsAndChemistryWorkDegreeSemester1) && is_null($biologyAndEarthWorkDegreeSemester1)){
            $row["work_degree_semester_1"] = null;
        }else{
            $row["work_degree_semester_1"] =
                $physicsAndChemistryWorkDegreeSemester1 + $biologyAndEarthWorkDegreeSemester1;
        }

        if(is_null($physicsAndChemistryExamDegreeSemester1) && is_null($biologyAndEarthExamDegreeSemester1)){
            $row["exam_degree_semester_1"] = null;
        }else{
            $row["exam_degree_semester_1"] =
                $physicsAndChemistryExamDegreeSemester1 + $biologyAndEarthExamDegreeSemester1;
        }


        if(is_null($physicsAndChemistryTotalSemester1) && is_null($biologyAndEarthTotalSemester1)){
            $row["total_semester_1"] = null;
        }else{
            $row["total_semester_1"] =
                $physicsAndChemistryTotalSemester1 +
                $biologyAndEarthTotalSemester1;
        }


        if(is_null($physicsAndChemistryWorkDegreeSemester2) && is_null($biologyAndEarthWorkDegreeSemester2)){
            $row["work_degree_semester_2"] = null;
        }else{
            $row["work_degree_semester_2"] =
                $physicsAndChemistryWorkDegreeSemester2 + $biologyAndEarthWorkDegreeSemester2;
        }

        if(is_null($physicsAndChemistryExamDegreeSemester2) && is_null($biologyAndEarthExamDegreeSemester2)){
            $row["exam_degree_semester_2"] = null;
        }else{
            $row["exam_degree_semester_2"] =
                $physicsAndChemistryExamDegreeSemester2 + $biologyAndEarthExamDegreeSemester2;

        }

        if(is_null($physicsAndChemistryTotalSemester2) && is_null($biologyAndEarthTotalSemester2)){
            $row["total_semester_2"] = null;
        }else{
            $row["total_semester_2"] =
                $physicsAndChemistryTotalSemester2 +
                $biologyAndEarthTotalSemester2;
        }
    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function processFrenchAndRussian($objectOfYearTemplate,&$row){
        $frenchMarks = Mark::with('Subject')
            ->where('student_studying_information_id',$objectOfYearTemplate->Marks[0]->student_studying_information_id)
            ->whereHas('Subject',function($query){
                return $query->where('code','F');
            })
            ->get();

        $russianMarks = Mark::with('Subject')
            ->where('student_studying_information_id',$objectOfYearTemplate->Marks[0]->student_studying_information_id)
            ->whereHas('Subject',function($query){
                return $query->where('code','R');
            })
            ->get();

        $chosenLangMarks = $russianMarks;
        if($this->frenchLanguageItsChosen($frenchMarks)){
            $chosenLangMarks = $frenchMarks;

        }


        $this->processStoredSubjectMarks($chosenLangMarks,$row);

    }

    /** @param Collection<Mark> */
    public function processStoredSubjectMarks($marks,&$row){
        foreach ($marks as $mark){
            if(is_null($mark->verbal) && is_null($mark->jobs_and_worksheets) && is_null($mark->activities_and_Initiatives) && is_null($mark->quiz)){
                $row["work_degree_semester_{$mark->Subject->semester}"] = null;
            }else{
                $row["work_degree_semester_{$mark->Subject->semester}"] = $mark->verbal + $mark->jobs_and_worksheets + $mark->activities_and_Initiatives + $mark->quiz;
            }

            $row["exam_degree_semester_{$mark->Subject->semester}"] =
                $mark->exam;

            if(is_null($row["work_degree_semester_{$mark->Subject->semester}"]) && is_null($row["exam_degree_semester_{$mark->Subject->semester}"])){
                $row["total_semester_{$mark->Subject->semester}"] = null;
            }else{
                $row["total_semester_{$mark->Subject->semester}"] =
                    $row["work_degree_semester_{$mark->Subject->semester}"] +
                    $row["exam_degree_semester_{$mark->Subject->semester}"];
            }
        }
    }

    private function frenchLanguageItsChosen($frenchMarks){
        if(
               $frenchMarks[0]->verbal
            || $frenchMarks[0]->jobs_and_worksheets
            || $frenchMarks[0]->activities_and_Initiatives
            || $frenchMarks[0]->quiz
            || $frenchMarks[0]->exam
            || $frenchMarks[1]->verbal
            || $frenchMarks[1]->jobs_and_worksheets
            || $frenchMarks[1]->activities_and_Initiatives
            || $frenchMarks[1]->quiz
            || $frenchMarks[1]->exam
        ){
            return true;
        }

        return false;

    }


    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function itsGeneralScienceSubject($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id >= 7 && $objectOfYearTemplate->base_level_id <= 9 && $objectOfYearTemplate->order == 7) {
            return true;
        }
        return false;
    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function itsFrenchOrRussianSubject($objectOfYearTemplate){
        if($objectOfYearTemplate->base_level_id >= 7 && $objectOfYearTemplate->order == 4) {
            return true;
        }
        return false;
    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function itsArabicSubject($objectOfYearTemplate){
        if($objectOfYearTemplate->base_subject_id == 3) {
            return true;
        }
        return false;
    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function itsOralSkillsSubject($objectOfYearTemplate){
        if($objectOfYearTemplate->writable_subject_name == "المهارات الشفوية") {
            return true;
        }
        return false;
    }

    /** @param YearGradesTemplate $objectOfYearTemplate */
    protected function itsWrittenSkillsSubject($objectOfYearTemplate){
        if($objectOfYearTemplate->writable_subject_name == "المهارات الكتابية") {
            return true;
        }
        return false;
    }

    /**
     * التحقق اذا كانت مادة السلوك
     * @param YearGradesTemplate $objectOfYearTemplate
     * @return bool
     *
     */
    protected function checkItsBehaviorSubject($objectOfYearTemplate){
        if(!is_null($objectOfYearTemplate->writable_subject_name) && $objectOfYearTemplate->writable_subject_name == 'السلوك'){
            return true;
        }
        return false;
     }

    /**
     * التحقق اذا كانت مادة رياضيات
     * @param YearGradesTemplate $objectOfYearTemplate
     * @return bool
     *
     */
    protected function checkItsMathSubject($objectOfYearTemplate){
        if($objectOfYearTemplate->base_subject_id == 7){
            return true;
        }
        return false;
    }

    protected function changeMarkToWrittenEstimate($mark){
        if(is_null($mark)){
            return $mark;
        }
        if($mark >= 0 && $mark <= 40){
            $mark = 'بحاجة الى تحسين';
        }
        elseif($mark > 40 && $mark <= 70  ){
            $mark = 'وسط';
        }
        elseif ($mark > 70 && $mark <=80){
            $mark = 'جيد';
        }
        elseif($mark > 80 && $mark<=90){
            $mark = 'جيد جدا';
        }
        elseif($mark > 90 && $mark<= 100){
            $mark = 'ممتاز';
        }
        return $mark;
    }

    /**
     * @param YearGradesTemplate $objectOfYearTemplate
     * @param array $row record from the outcome
     * @return bool
     */
    protected function checkTheMarkLessThanPercentage($objectOfYearTemplate,$row,$percentage){
        //we get max_degree for the writable subjects from $objectOfYearTemplate and for stored subjects from the relations
        $maxDegree = isset($objectOfYearTemplate->BaseSubject)
            ?$objectOfYearTemplate->BaseSubject->BaseLevelSubjects[0]->Rule->max_degree
            :$objectOfYearTemplate['max_degree'];
        $failurePercentageMark = $maxDegree * ($percentage/100);
        if($row['final_degree'] < $failurePercentageMark){
            return true;
        }
        return false;
    }




}
