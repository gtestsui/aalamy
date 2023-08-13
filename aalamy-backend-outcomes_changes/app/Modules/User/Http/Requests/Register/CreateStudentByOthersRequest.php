<?php

namespace Modules\User\Http\Requests\Register;

use Illuminate\Validation\Rule;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\UserServices;

class CreateStudentByOthersRequest extends BaseRegisterRequest
{


    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        $user = $this->user();
        UserServices::checkRoles($user,['educator','school','teacher']);

        if(isset($this->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $studentPermissionClass = new StudentPermissionClass($teacher);
            $studentPermissionClass->checkIfHavePermission('create')
                ->checkCreate();

            return true;
        }else{
            $studentCountModuleClass = StudentCountModuleClass::createByOwner($user);
            $studentCountModuleClass->check();
        }

        return true;
    }

    public function rules(){
        $baseRules = Parent::rules();//userRules
        $myRules = $this->myRules();
        $accountRules = $this->additionalRulesByAccountType(
            'student'
        );
        return  array_merge($baseRules,$myRules,$accountRules);

    }

    public function myRules(){
        return  [
            'password' =>'required|min:6|confirmed',
            'password_confirmation' =>'required_with:password',



            //BasicInfo
            'basic_information' => 'array|required',
            'basic_information.father_fname' => 'required',
            'basic_information.mother_fname' => 'required',
            'basic_information.mother_lname' => 'nullable',
            'basic_information.grandfather_name' => 'nullable',
            'basic_information.place_of_birth' => 'required',
            'basic_information.place_of_birth_image' => 'nullable',
            'basic_information.place_of_registration' => 'required',
            'basic_information.number_of_registration' => 'required',
            'basic_information.religion' => ['required',Rule::in(['muslim','christian','other'])],
            'basic_information.passport_or_residence_card_number' => 'nullable',
            'basic_information.address' => 'required',
            'basic_information.residence_type' => 'nullable',
            'basic_information.residence_ownership' => ['nullable',Rule::in(['own','rent','borrow'])],
            'basic_information.distance_between_residence_and_school' => 'nullable|numeric',
            'basic_information.process_of_going_to_school' => ['nullable',Rule::in(['walking','vehicle','bicycle'])],
            'basic_information.telephone' => 'nullable',
            'basic_information.mobile' => 'nullable',
            'basic_information.curriculum_type' => ['nullable',Rule::in(['category_a','category_b'])],
            'basic_information.sons_of_martyrs' => 'required',
            'basic_information.coming_from_school_name' => 'nullable',
            'basic_information.student_situation' => ['nullable',Rule::in(['arrival','resident'])],
            'basic_information.alhasakah_foreigners' => 'required',
            'basic_information.inclusion_of_people_with_disabilities' => 'required',
            'basic_information.muffled' => 'required',
            'basic_information.outstanding_test' => 'required',
            'basic_information.notes' => 'nullable',
            'basic_information.first_year' => 'required',
//
//            //FamilyInformation
            'family_information' => 'array|required',
            'family_information.father_work' => 'nullable',
            'family_information.father_phone' => 'nullable',
            'family_information.mother_living_with_father' => 'required',
            'family_information.mother_work' => 'nullable',
            'family_information.mother_phone' => 'nullable',
            'family_information.father_studying' => 'nullable',
            'family_information.mother_studying' => 'nullable',
            'family_information.family_income' => 'nullable|numeric',
            'family_information.father_and_mother_are_relatives' => 'required',
            'family_information.older_brothers_count' => 'nullable|numeric',
            'family_information.younger_brothers_count' => 'nullable|numeric',
            'family_information.older_sisters_count' => 'nullable|numeric',
            'family_information.younger_sisters_count' => 'nullable|numeric',
            'family_information.have_uncle_from_father' => 'required',
            'family_information.have_uncle_from_mother' => 'required',
            'family_information.living_in_same_house' => 'required',
            'family_information.have_internet_in_the_house' => 'required',
            'family_information.workers_from_the_family_count' => 'nullable|numeric',
//
//            //OtherInformation
            'other_information' => 'array|required',
            'other_information.aid_provided_to_the_student' => 'nullable|string',
            'other_information.underwent_early_childhood_program' => 'nullable|boolean',
//
//            //SocialAndPersonalInformation
            'social_and_personal_information' => 'required|array',
            'social_and_personal_information.trouble_distinguishing' => 'required',
            'social_and_personal_information.weak_memory' => 'required',
            'social_and_personal_information.behavioral_abnormalities' => 'required',
            'social_and_personal_information.hyperactivity' => 'required',
            'social_and_personal_information.tends_to_behave_aggressively' => 'required',
            'social_and_personal_information.introvert' => 'required',
            'social_and_personal_information.difficulty_with_learning' => 'required',
            'social_and_personal_information.it_takes_a_lot_to_motivate_him' => 'required',
            'social_and_personal_information.trust_himself' => 'required',
            'social_and_personal_information.take_responsibility' => 'required',
            'social_and_personal_information.respects_the_order' => 'required',
            'social_and_personal_information.accept_criticism_and_correct_mistakes' => 'required',
            'social_and_personal_information.cooperating' => 'required',
            'social_and_personal_information.he_expresses_his_opinion_boldly' => 'required',
            'social_and_personal_information.controlling_and_showing_off' => 'required',
            'social_and_personal_information.contribute_to_activities' => 'required',
            'social_and_personal_information.he_perseveres_in_his_work' => 'required',
            'social_and_personal_information.maintains_public_facilities' => 'required',
            'social_and_personal_information.respect_the_rules_and_regulations_of_the_school' => 'required',
            'social_and_personal_information.suffers_from_jealousy' => 'required',
            'social_and_personal_information.committed' => 'required',
            'social_and_personal_information.leading' => 'required',
            'social_and_personal_information.initiative' => 'required',
            'social_and_personal_information.careful_observation' => 'required',
            'social_and_personal_information.able_to_simulate' => 'required',
            'social_and_personal_information.creator' => 'required',
            'social_and_personal_information.self_made' => 'required',
            'social_and_personal_information.disciplined' => 'required',
            'social_and_personal_information.hard_working' => 'required',
            'social_and_personal_information.emotionally_balanced' => 'required',
            'social_and_personal_information.tends_to_rebel' => 'required',
            'social_and_personal_information.artistic_hobbies' => 'required',
            'social_and_personal_information.sports_hobbies' => 'required',
            'social_and_personal_information.other_hobbies' => 'required',
            'social_and_personal_information.another_traits' => 'nullable',

        ];
    }




}
