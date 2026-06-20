<?php

return [
    'order_placed' => [
        'title' => 'تم تقديم الطلب بنجاح',
        'message' => 'تم تقديم طلبك رقم :order بنجاح وهو في انتظار التأكيد.',
    ],
    'new_order_vendor' => [
        'title' => 'طلب جديد وارد',
        'message' => 'لديك طلب جديد رقم :order يحتوي على منتجاتك.',
    ],
    'order_status_updated' => [
        'title' => 'تم تحديث حالة الطلب',
        'message' => 'تم تحديث حالة طلبك رقم :order إلى ":status".',
    ],
    'product_approved' => [
        'title' => 'تمت الموافقة على المنتج',
        'message' => 'تمت الموافقة على منتجك ":product" من قبل الإدارة وهو متاح الآن.',
    ],
    'product_rejected' => [
        'title' => 'تم رفض المنتج',
        'message' => 'تم رفض منتجك ":product".:reason',
    ],
    'vendor_request_updated' => [
        'title' => 'طلب البائع :status',
        'message' => 'حالة طلب ملف البائع الخاص بك أصبحت :status.:reason',
    ],
    'vendor_subscription_confirmed' => [
        'title' => 'تم تأكيد دفع الاشتراك',
        'message' => 'تم تأكيد دفع اشتراك البائع من قبل الإدارة.',
    ],
    'vendor_subscription_expiring' => [
        'title' => 'اشتراكك على وشك الانتهاء',
        'message' => 'اشتراكك كبائع ينتهي خلال :days يوم/أيام. سارع بالتجديد لضمان عدم إخفاء منتجاتك من المتجر.',
    ],
    'vendor_subscription_grace' => [
        'title' => 'فترة سماح الاشتراك',
        'message' => 'انتهى اشتراكك كبائع. لديك :days يوم/أيام للتجديد قبل إخفاء منتجاتك من المتجر.',
    ],
    'vendor_subscription_expired' => [
        'title' => 'انتهى الاشتراك',
        'message' => 'انتهى اشتراكك بالكامل. جدّد الاشتراك لاستعادة ظهور منتجاتك في المتجر.',
    ],
    'contact_message_received' => [
        'title' => 'رسالة تواصل جديدة',
        'message' => 'أرسل :name رسالة: :subject',
    ],
    'dxn_team_request_received' => [
        'title' => 'طلب فريق DXN جديد',
        'message' => 'قدّم :name طلب بناء فريق DXN.',
    ],
    'dxn_team_request_updated' => [
        'title' => 'طلب DXN :status',
        'message' => 'تم تحديث طلب DXN الخاص بـ ":team_name" إلى: :status.',
    ],
    'special_request_offer' => [
        'title' => 'تم تعيين عرض للطلب الخاص',
        'message' => 'تم تعيين عرض بكمية :quantity لطلبك الخاص على ":product". يمكنك الشراء الآن!',
    ],
    'new_review' => [
        'title' => 'تعليق/مراجعة جديدة',
        'message' => 'تم إرسال مراجعة جديدة من :name وهي في انتظار الموافقة.',
    ],
    'new_product' => [
        'title' => 'منتج جديد',
        'message' => 'اكتشف منتجنا الجديد: :product',
    ],
    'brand_new_product' => [
        'title' => ':brand أضاف منتجاً جديداً',
        'message' => 'أضاف :brand منتج :product إلى المتجر.',
    ],
    'new_package' => [
        'title' => 'باكيدج جديد',
        'message' => 'اكتشف باكيدجنا الجديد: :package',
    ],
    'vendor_package_submitted' => [
        'title' => 'باكيدج جديد بانتظار الموافقة',
        'message' => 'قدّم :brand باكيدجاً جديداً ":package" للمراجعة.',
    ],
    'package_approved' => [
        'title' => 'تمت الموافقة على الباكيدج',
        'message' => 'تمت الموافقة على باكيدج ":package" وهو متاح الآن في المتجر.',
    ],
    'package_rejected' => [
        'title' => 'تم رفض الباكيدج',
        'message' => 'تم رفض باكيدج ":package".:reason',
    ],
    'missed_packages' => [
        'title' => 'باكيدجات قد تكون فاتتك',
        'message' => 'بعض الباكيدجات المميزة بانتظارك — اضغط للاطلاع.',
    ],
    'rejection_reason' => ' السبب: :reason',
    'vendor_reason' => ' السبب: :reason',
    'status' => [
        'pending' => 'قيد الانتظار',
        'confirmed' => 'مؤكد',
        'preparing' => 'قيد التحضير',
        'ready' => 'جاهز',
        'delivered' => 'تم التسليم',
        'cancelled' => 'ملغي',
        'approved' => 'موافق عليه',
        'rejected' => 'مرفوض',
        'rejected_with_notes' => 'مرفوض مع ملاحظات',
    ],
];
