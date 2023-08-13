<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;

class StudentSocialAndPersonalInformation extends Model
{
    use DefaultGlobalScopes;

    use SoftDelete;
    use Searchable;
    use Orderable;
    use SoftDelete;


    public static function customizedBooted(){}

    protected $table = 'student_social_and_personal_traits_information';

    protected $fillable = [
        'student_id',
        'trouble_paying_attention',//لديه صعوبة في الانتباه
        'trouble_distinguishing',//لديه صعوبة في التميز
        'weak_memory',//لديه ضعف في الذاكرة
        'behavioral_abnormalities',//لديه انحرافات سلوكية
        'hyperactivity',//فرط النشاط
        'tends_to_behave_aggressively',//يميل الى التصرف بعدوانية
        'introvert',//انطوائي
        'difficulty_with_learning',//صعوبة تجاه التعلم
        'it_takes_a_lot_to_motivate_him',//يحتاج الكثير لاثارة دافعيته
        'trust_himself',//يثق بنفسه
        'take_responsibility',//يتحمل المسؤولية
        'respects_the_order',//يحترم النظام
        'accept_criticism_and_correct_mistakes',//يقبل النقد ويصحح الاخطاء
        'cooperating',//متعاون
        'he_expresses_his_opinion_boldly',//يبادر في ابداء رأيه بجرأة
        'controlling_and_showing_off',//يميل الى السيطرة وحب الظهور
        'contribute_to_activities',//يساهم في النشاطات
        'he_perseveres_in_his_work',//يثابر في اداء عمله
        'maintains_public_facilities',//يحافظ على المرافق العامة
        'respect_the_rules_and_regulations_of_the_school',//يحترم النظام والقوانين في المدرسة
        'suffers_from_jealousy',//يعاني من الغيرة
        'committed',//ملتزم
        'leading',//قيادي
        'initiative',//مبادر
        'careful_observation',//دقيق الملاحظة
        'able_to_simulate',//قادر على المحاكاة
        'creator',//مبدع
        'self_made',//عصامي
        'disciplined',//منضبط
        'hard_working',//دؤوب على العمل
        'emotionally_balanced',//متزن انفعاليا
        'tends_to_rebel',//يميل الى التمرد
        'artistic_hobbies',//لديه هوايات فنية
        'sports_hobbies',//هوايات رياضية
        'other_hobbies',//هوايات اخرى
        'another_traits',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];


	protected $casts = [
        'trouble_paying_attention'  => 'boolean',//لديه صعوبة في الانتباه
        'trouble_distinguishing'  => 'boolean',//لديه صعوبة في التميز
        'weak_memory'  => 'boolean',//لديه ضعف في الذاكرة
        'behavioral_abnormalities'  => 'boolean',//لديه انحرافات سلوكية
        'hyperactivity'  => 'boolean',//فرط النشاط
        'tends_to_behave_aggressively'  => 'boolean',//يميل الى التصرف بعدوانية
        'introvert'  => 'boolean',//انطوائي
        'difficulty_with_learning'  => 'boolean',//صعوبة تجاه التعلم
        'it_takes_a_lot_to_motivate_him'  => 'boolean',//يحتاج الكثير لاثارة دافعيته
        'trust_himself'  => 'boolean',//يثق بنفسه
        'take_responsibility'  => 'boolean',//يتحمل المسؤولية
        'respects_the_order'  => 'boolean',//يحترم النظام
        'accept_criticism_and_correct_mistakes'  => 'boolean',//يقبل النقد ويصحح الاخطاء
        'cooperating'  => 'boolean',//متعاون
        'he_expresses_his_opinion_boldly'  => 'boolean',//يبادر في ابداء رأيه بجرأة
        'controlling_and_showing_off'  => 'boolean',//يميل الى السيطرة وحب الظهور
        'contribute_to_activities'  => 'boolean',//يساهم في النشاطات
        'he_perseveres_in_his_work'  => 'boolean',//يثابر في اداء عمله
        'maintains_public_facilities'  => 'boolean',//يحافظ على المرافق العامة
        'respect_the_rules_and_regulations_of_the_school'  => 'boolean',//يحترم النظام والقوانين في المدرسة
        'suffers_from_jealousy'  => 'boolean',//يعاني من الغيرة
        'committed'  => 'boolean',//ملتزم
        'leading'  => 'boolean',//قيادي
        'initiative'  => 'boolean',//مبادر
        'careful_observation'  => 'boolean',//دقيق الملاحظة
        'able_to_simulate'  => 'boolean',//قادر على المحاكاة
        'creator'  => 'boolean',//مبدع
        'self_made'  => 'boolean',//عصامي
        'disciplined'  => 'boolean',//منضبط
        'hard_working'  => 'boolean',//دؤوب على العمل
        'emotionally_balanced'  => 'boolean',//متزن انفعاليا
        'tends_to_rebel'  => 'boolean',//يميل الى التمرد
        'artistic_hobbies'  => 'boolean',//لديه هوايات فنية
        'sports_hobbies'  => 'boolean',//هوايات رياضية
        'other_hobbies'  => 'boolean',//هوايات اخرى
    ];


    private $mySearchableFields = [

    ];


     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [


    ];



}
