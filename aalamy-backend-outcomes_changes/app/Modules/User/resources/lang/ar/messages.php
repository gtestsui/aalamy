<?php


return[


    //////////////////////////
    'invalid_account_type' => 'نمط الحساب غير صحيح',
    'invalid_confirmation_code' => 'رمز التأكيد خاطئ',
    'invalid_confirmation_code_expiration_date' => 'انتهى وقت تفعيل الرمز حاول مجددا بعد توليد رمز جديد',
    'wrong_credentials' => 'البيانات المدخلة غير متطابقة',
    'huge_logged_devices_count' => 'لا يمكنك تسجيل الدخول بأكثر من '.
        config('User.panel.logged_devices_count').
        ' أجهزة ',
    'invalid_login_service' => 'التسجيل عبر هذه الخدمة غير متاح حاليا',
    'not_active_account' => 'حسابك غير مفعل',
    'not_active_school_account' => 'حساب مدرستك غير مفعل',
    'not_verified_account' => 'عليك تأكيد حسابك اولا',
    'verification_code' => 'رمز تأكيد الحساب',
    'forget_password_invalid_email' => 'الايميل المدخل غير صحيح',
    'invalid_parent_code' => 'الكود المدخل غير صحيح',
    'doesnt_have_parent_email' => 'هذا الطالب لا يملك بريد الكتروني خاص بالأب',
    'confirmation_code_allowed_date' =>
        'لا يمكنك محاولة اعادة ارسال الرمز حتى'
        .'(:dateTime)',
    'you_dont_have_an_previous_invitation' => 'لا يمكنك الانضمام مباشرة الى مدرسة اذا لم تكن لديك دعوة',
    'wrong_login_with_service' => 'عليك انشاء حساب في الموقع أولا',

];
