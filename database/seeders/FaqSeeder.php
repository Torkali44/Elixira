<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question_en' => 'What is Elixira?',
                'answer_en' => 'Elixira is a curated wellness marketplace connecting you with premium skincare, superfoods, and beauty brands from verified vendors across the region.',
                'question_ar' => 'ما هي إكسيرا؟',
                'answer_ar' => 'إكسيرا هي منصة رفاهية متكاملة تربطك بأفضل منتجات العناية بالبشرة والمكملات الغذائية الطبيعية ومستحضرات الجمال من بائعين موثوقين في المنطقة.',
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'question_en' => 'How do I track my order?',
                'answer_en' => 'You can track your order using the "Track Order" page in the navigation menu. Enter your order number and email address to see real-time status updates.',
                'question_ar' => 'كيف أتتبع طلبيتي؟',
                'answer_ar' => 'يمكنك تتبع طلبيتك من خلال صفحة "تتبع الطلب" في قائمة التصفح. أدخل رقم الطلب وبريدك الإلكتروني للاطلاع على آخر تحديثات الشحن.',
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'question_en' => 'What payment methods do you accept?',
                'answer_en' => 'We accept all major credit and debit cards (Visa, Mastercard), as well as Apple Pay. All transactions are secured with industry-standard encryption.',
                'question_ar' => 'ما طرق الدفع المتاحة؟',
                'answer_ar' => 'نقبل جميع بطاقات الائتمان والخصم الرئيسية (فيزا، ماستركارد) بالإضافة إلى Apple Pay. جميع المعاملات مؤمّنة بأحدث معايير التشفير.',
                'sort_order' => 3,
                'is_published' => true,
            ],
            [
                'question_en' => 'Can I return a product?',
                'answer_en' => 'Yes. We offer a hassle-free return window of 14 days from the date of delivery, provided the product is unused and in its original packaging. Contact our support team to initiate a return.',
                'question_ar' => 'هل يمكنني إرجاع منتج؟',
                'answer_ar' => 'نعم. نتيح لك نافذة إرجاع مريحة مدتها 14 يومًا من تاريخ التسليم، شريطة أن يكون المنتج غير مستخدم وفي عبوته الأصلية. تواصل مع فريق الدعم لبدء طلب الإرجاع.',
                'sort_order' => 4,
                'is_published' => true,
            ],
            [
                'question_en' => 'How do I become a vendor on Elixira?',
                'answer_en' => 'Register for a vendor account and complete your profile with your brand details and verification documents. Our team reviews applications within 2–3 business days.',
                'question_ar' => 'كيف أصبح بائعًا على إكسيرا؟',
                'answer_ar' => 'سجّل حسابًا كبائع وأكمل ملفك الشخصي بتفاصيل علامتك التجارية ووثائق التحقق. يراجع فريقنا الطلبات خلال 2 إلى 3 أيام عمل.',
                'sort_order' => 5,
                'is_published' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question_en' => $faq['question_en']],
                $faq
            );
        }
    }
}
