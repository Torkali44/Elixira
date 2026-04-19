<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'لازم تقبل :attribute.',
    'active_url' => ':attribute مهوب رابط صحيح.',
    'after' => ':attribute لازم يكون تاريخ بعد :date.',
    'alpha' => ':attribute لازم يكون حروف بس.',
    'alpha_dash' => ':attribute لازم يكون حروف وأرقام وشرطات بس.',
    'alpha_num' => ':attribute لازم يكون حروف وأرقام بس.',
    'array' => ':attribute لازم يكون قائمة.',
    'before' => ':attribute لازم يكون تاريخ قبل :date.',
    'between' => [
        'numeric' => ':attribute لازم يكون بين :min و :max.',
        'file' => ':attribute لازم يكون حجمه بين :min و :max كيلوبايت.',
        'string' => ':attribute لازم يكون عدد حروفه بين :min و :max.',
        'array' => ':attribute لازم يكون عدد عناصره بين :min و :max.',
    ],
    'boolean' => 'حقل :attribute لازم يكون صح أو خطأ.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'date' => ':attribute مهوب تاريخ صحيح.',
    'date_format' => ':attribute ما يطابق الصيغة :format.',
    'different' => ':attribute و :other لازم يكونون مختلفين.',
    'digits' => ':attribute لازم يكون :digits أرقام.',
    'digits_between' => ':attribute لازم يكون بين :min و :max أرقام.',
    'dimensions' => ':attribute أبعاد الصورة مهيب صحيحة.',
    'distinct' => 'حقل :attribute له قيمة مكررة.',
    'email' => ':attribute لازم يكون بريد إلكتروني صحيح.',
    'exists' => ':attribute المختار مهوب موجود.',
    'file' => ':attribute لازم يكون ملف.',
    'filled' => 'حقل :attribute لازم يكون له قيمة.',
    'image' => ':attribute لازم يكون صورة.',
    'in' => ':attribute المختار مهوب صحيح.',
    'in_array' => 'حقل :attribute مهوب موجود في :other.',
    'integer' => ':attribute لازم يكون عدد صحيح.',
    'ip' => ':attribute لازم يكون عنوان IP صحيح.',
    'json' => ':attribute لازم يكون نص JSON صحيح.',
    'max' => [
        'numeric' => ':attribute ما يصير أكبر من :max.',
        'file' => ':attribute ما يصير حجمه أكبر من :max كيلوبايت.',
        'string' => ':attribute ما يصير عدد حروفه أكثر من :max.',
        'array' => ':attribute ما يصير عدد عناصره أكثر من :max.',
    ],
    'mimes' => ':attribute لازم يكون ملف من نوع: :values.',
    'mimetypes' => ':attribute لازم يكون ملف من نوع: :values.',
    'min' => [
        'numeric' => ':attribute لازم يكون على الأقل :min.',
        'file' => ':attribute لازم يكون حجمه على الأقل :min كيلوبايت.',
        'string' => ':attribute لازم يكون عدد حروفه على الأقل :min.',
        'array' => ':attribute لازم يكون فيه على الأقل :min عناصر.',
    ],
    'not_in' => ':attribute المختار مهوب صحيح.',
    'numeric' => ':attribute لازم يكون رقم.',
    'present' => 'حقل :attribute لازم يكون موجود.',
    'regex' => 'صيغة :attribute مهيب صحيحة.',
    'required' => 'حقل :attribute مطلوب يا الغالي.',
    'required_if' => 'حقل :attribute مطلوب لما :other يكون :value.',
    'required_unless' => 'حقل :attribute مطلوب إلا إذا :other كان في :values.',
    'required_with' => 'حقل :attribute مطلوب لما :values يكون موجود.',
    'required_with_all' => 'حقل :attribute مطلوب لما :values يكونون موجودين.',
    'required_without' => 'حقل :attribute مطلوب لما :values مهوب موجود.',
    'required_without_all' => 'حقل :attribute مطلوب لما ولا واحد من :values موجود.',
    'same' => ':attribute و :other لازم يتطابقون.',
    'size' => [
        'numeric' => ':attribute لازم يكون :size.',
        'file' => ':attribute لازم يكون حجمه :size كيلوبايت.',
        'string' => ':attribute لازم يكون عدد حروفه :size.',
        'array' => ':attribute لازم يحتوي على :size عنصر.',
    ],
    'string' => ':attribute لازم يكون نص.',
    'timezone' => ':attribute لازم يكون منطقة زمنية صحيحة.',
    'unique' => ':attribute مسجل من قبل.',
    'uploaded' => 'فشل رفع :attribute.',
    'url' => 'صيغة :attribute مهيب صحيحة.',
    'uuid' => ':attribute لازم يكون UUID صحيح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'الاسم',
        'username' => 'اسم المستخدم',
        'email' => 'البريد الإلكتروني',
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city' => 'المدينة',
        'country' => 'الدولة',
        'address' => 'العنوان',
        'phone' => 'رقم الجوال',
        'mobile' => 'رقم الجوال',
        'age' => 'العمر',
        'sex' => 'الجنس',
        'gender' => 'النوع',
        'day' => 'اليوم',
        'month' => 'الشهر',
        'year' => 'السنة',
        'hour' => 'الساعة',
        'minute' => 'الدقيقة',
        'second' => 'الثانية',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'description' => 'الوصف',
        'excerpt' => 'المقتطف',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'available' => 'متاح',
        'size' => 'الحجم',
    ],

];
