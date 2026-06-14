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
    'contact_message_received' => [
        'title' => 'رسالة تواصل جديدة',
        'message' => 'أرسل :name رسالة: :subject',
    ],
    'dxn_team_request_received' => [
        'title' => 'طلب فريق DXN جديد',
        'message' => 'قدّم :name طلب بناء فريق DXN.',
    ],
    'special_request_offer' => [
        'title' => 'تم تعيين عرض للطلب الخاص',
        'message' => 'تم تعيين عرض بكمية :quantity لطلبك الخاص على ":product". يمكنك الشراء الآن!',
    ],
    'new_review' => [
        'title' => 'تعليق/مراجعة جديدة',
        'message' => 'تم إرسال مراجعة جديدة من :name وهي في انتظار الموافقة.',
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
