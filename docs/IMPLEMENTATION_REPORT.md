# Elixira — تقرير التنفيذ الشامل

> آخر تحديث: يونيو 2026  
> الإطار: Laravel 12 · PHP 8.2 · Pest 3 · Tailwind 3 · Alpine 3

---

## 1. نظرة عامة على المنصة

**Elixira** منصة تجارة إلكترونية متعددة البائعين (DXN / Wellness) تدعم:

- واجهة متجر عامة (عربي / إنجليزي)
- لوحة تحكم Admin
- لوحة تحكم Vendor (بائع)
- نظام عضوية DXN وأسعار مختلفة للعضو / غير العضو
- تسعير حسب الدولة (السعودية KSA / الإمارات UAE)

---

## 2. الواجهة العامة (Storefront)

| الميزة | المسار | الوصف |
|--------|--------|--------|
| الصفحة الرئيسية | `/` | أقسام ديناميكية (Home Sections) |
| المنتجات | `/menu` | فلترة حسب الدولة + عرض البطاقات |
| تفاصيل منتج | `/menu/{item}` | وصف قصير/طويل ثنائي اللغة، معرض صور، تقييمات، منتجات ذات صلة بالـ Tags |
| الباكيدجات | `/packages` | فلترة حسب الدولة |
| تفاصيل باكيدج | `/packages/{package}` | منتجات مضمّنة + وصف تفصيلي |
| البحث | `/search?q=` | منتجات + مدونات + أسئلة شائعة |
| العلامات التجارية | `/brands` | عرض العلامات النشطة |
| المدونة | `/blogs` | مقالات منشورة |
| الأسئلة الشائعة | `/faqs` | FAQ منشور |
| الشهادات | `/testimonials` | إرسال شهادة من الزوار |
| السلة | `/cart` | منتجات + باكيدجات |
| الدفع | `POST /checkout` | إنشاء طلب |
| تتبع الطلب | `/track-order` | فاتورة PDF/HTML |
| DXN | `/become-dxn-distributor` | طلب انضمام + عضو حالي |
| التواصل | `/contact` | رسائل للأدمن |
| طلبات خاصة | `POST /special-requests` | طلب منتج مخصص |
| تبديل اللغة | `/lang/{locale}` | `en` / `ar` |
| تبديل الثيم | `/theme/{theme}` | dark/light |

### تسعير الدولة والعضوية
- جلسة `shopping_country` تحفظ اختيار المستخدم (KSA / UAE).
- دروب‌داون "اختر دولتك" في `/menu` و `/packages`.
- أسعار العضو (DXN verified) vs الضيف لكل دولة.

---

## 3. المصادقة والتسجيل

| الميزة | التفاصيل |
|--------|----------|
| تسجيل مستخدم | Breeze + تحقق بريد OTP |
| OTP | إرسال عبر Gmail SMTP، صلاحية قابلة للضبط (`OTP_TTL_MINUTES`) |
| إنتاج | لا تظهر رسائل تطوير OTP في الواجهة |
| CSRF | معالجة 419 في Laravel 12 (`bootstrap/app.php`) |
| هاتف التسجيل | مكوّن `phone-country-input` (علم + كود + اسم الدولة) |
| أدوار | `user` · `vendor` · `admin` |

### أوامر Artisan للتطوير فقط (لا تُستخدم في الإنتاج)
- `verification:show-otp {email}`
- `mail:diagnose`
- `mail:test`

---

## 4. لوحة الأدمن (`/admin`)

### إدارة المحتوى
- **Categories** — أقسام ثنائية اللغة
- **Products** — إضافة/تعديل/موافقة/رفض
- **Packages** — نفس نظام الموافقة
- **Blogs · FAQs · Reviews · Home Sections**
- **Brands** — تعديل العلامات
- **Avatar Options** — صور افتراضية للملف الشخصي
- **Translations** — تعديل نصوص الواجهة من لوحة التحكم

### الموافقات (Products & Packages)
| الحالة | المعنى |
|--------|--------|
| `new` | `pending` وأقل من 24 ساعة |
| `pending` | `pending` وأكثر من 24 ساعة |
| `approved` | ظاهر في المتجر |
| `rejected` / `rejected_with_notes` | مرفوض مع سبب |

- إشعار للأدمن عند إرسال منتج/باكيدج من البائع.
- Badge في السايدبار للعناصر الجديدة.
- **Home Sections** قبل **Translations** في أسفل القائمة.

### المستخدمون والبائعون
- قائمة مستخدمين (المُتحقق من بريدهم فقط)
- فلتر DXN members باللون
- طلبات انضمام بائع + اشتراك شهري/سنوي
- **10 فتحات مجانية** — يُحسب فقط البائعون `approved` + غير موقوفين + علامة نشطة
- تأكيد الدفع من الأدمن يبدأ الاشتراك (ليس عند الدفع وحده)

### الطلبات والتقارير
- Orders · Special Requests · Contact Messages
- DXN Team Requests · Sponsor Codes
- Reports: orders, products, vendors, brands, financials
- Newsletter subscribers

---

## 5. لوحة البائع (`/vendor`)

| الميزة | الوصف |
|--------|--------|
| Onboarding | خطوات متعددة + رفع شعار + دول الخدمة |
| Products | إضافة منتج → `pending` حتى موافقة الأدمن |
| Packages | اختيار منتجات معتمدة + موافقة أدمن |
| Orders | طلبات العلامة فقط |
| Special Requests | عروض خاصة للعملاء |
| Brand | تعديل بيانات العلامة |
| اشتراك | grace period بعد انتهاء الاشتراك |

### حقول المنتج والباكيدج (ثنائي اللغة)
- **Name (EN) / Name (AR)**
- **Description (EN) / Description (AR)**
- **Long Description (EN) / Long Description (AR)** — تبويبات منفصلة

---

## 6. السلة والطلبات

- دعم منتجات (`item_id`) وباكيدجات (`p_{id}`)
- التحقق من المخزون + عروض خاصة (Private Offers)
- خصم مخزون عند الطلب
- نقاط مكافأة (`reward_points`)
- فواتير للمستخدم ولوحة الأدمن

---

## 7. نظام Tags والمحتوى المرتبط

- Tags على: منتجات، باكيدجات، مدونات، شهادات، علامات
- `TagService`: منتجات/مدونات/باكيدجات/علامات ذات صلة
- المنتحات المرئية تتطلب: `approved` + أسعار دولة + بائع نشط (إن وُجد)

---

## 8. الإشعارات

- جدول `notifications` لكل مستخدم
- `UserNotifier` / `AdminNotifier`
- مفاتيح ترجمة في `resources/lang/*/notifications.php`

---

## 9. البريد الإلكتروني

- SMTP (Gmail App Password موصى به)
- `EmailVerificationOtpMail`
- `COMPANY_EMAIL` و `MAIL_FROM_*` في `.env`
- سجل OTP منفصل في التطوير: `storage/logs/otp.log`

---

## 10. قاعدة البيانات — جداول رئيسية

`users` · `vendor_profiles` · `brands` · `categories` · `items` · `item_images` · `item_country_prices` · `packages` · `package_item` · `package_country_prices` · `orders` · `order_items` · `tags` · `taggables` · `blogs` · `faqs` · `reviews` · `notifications` · `special_requests` · `special_item_offers` · `dxn_team_requests` · `contact_messages` · `newsletter_subscribers` · `home_page_sections` · `avatar_options` · `ratings` · `reservations`

---

## 11. تخزين الصور (مهم للإنتاج)

| النوع | مسار الحفظ الفعلي | مسار العرض (URL) |
|-------|-------------------|------------------|
| منتج رئيسي | `storage/app/public/items/` | `/storage/items/...` |
| معرض منتج | `storage/app/public/items/gallery/` | `/storage/items/gallery/...` |
| باكيدج | `storage/app/public/packages/` | `/storage/packages/...` |
| علامة تجارية | `storage/app/public/brands/` | `/storage/brands/...` |
| صورة مستخدم | `storage/app/public/users/avatars/` | `/storage/users/avatars/...` |
| تقييم | `storage/app/public/ratings/` | `/storage/ratings/...` |

> كل الرفع يستخدم `->store(..., 'public')` ويعرض عبر `asset('storage/'.$path)`.

**شرط الظهور:** وجود symlink  
`public/storage` → `storage/app/public`  
(مفصّل في `docs/NAMECHEAP_DEPLOYMENT.md`)

---

## 12. الاختبارات

```bash
php artisan test
```

| النوع | العدد | الحالة |
|-------|-------|--------|
| Feature tests | 84+ | ✅ كلها ناجحة |
| Unit tests | 2 | ✅ ناجحة |
| **المجموع** | **86** | **288 assertion** |

ملفات الاختبار الرئيسية:
- `tests/Feature/SiteEnhancementsTest.php`
- `tests/Feature/TagRelationsTest.php`
- `tests/Feature/Auth/*`
- `tests/Feature/PrivateOfferCartTest.php`
- `tests/Unit/ItemLocalizationTest.php`

---

## 13. إصلاحات الفحص الشامل (يونيو 2026)

| المشكلة | الإصلاح |
|---------|---------|
| `ReservationController` غير مستورد في `routes/web.php` | ✅ أُضيف `use` — كان يكسر route list |
| 5 اختبارات فاشلة (منتجات بدون أسعار دولة) | ✅ أُصلحت بيانات الاختبار + helper `createTestItem()` |
| ملفات تطوير قديمة في الجذر | ✅ حُذفت: `test.php`, `render_test.php`, `test_menu.html`, `test_view.html`, `item.html` |
| `.env.example` غير مناسب للإنتاج | ✅ حُدّث لإعدادات Namecheap |

---

## 14. أوامر Cron موصى بها (الإنتاج)

| الأمر | التكرار | الغرض |
|-------|---------|--------|
| `php artisan vendors:process-subscriptions` | يومياً | تنبيهات انتهاء اشتراك البائع |
| `php artisan points:reset-monthly` | أول كل شهر | تصفير نقاط المكافأة |

> على Namecheap بدون terminal: استخدم **Cron Jobs** في cPanel.

---

## 15. متغيرات `.env` الأساسية

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_CONNECTION=mysql
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
MAIL_* / COMPANY_EMAIL / ADMIN_EMAIL / ADMIN_PASSWORD
```

---

## 16. هيكل المجلدات

```
elixira/
├── app/           # Controllers, Models, Support, Mail, Requests
├── bootstrap/     # app.php (middleware, exceptions)
├── config/        # إعدادات التطبيق
├── database/      # migrations, seeders
├── docs/          # هذا الملف + دليل الرفع
├── public/        # Document Root على السيرفر
├── resources/     # views, lang
├── routes/        # web.php, auth.php
├── storage/       # logs, uploads (app/public)
└── tests/         # Pest tests
```

---

## 17. Seeders للنشر الأول

```bash
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=DatabaseSeeder
```

يُنشئ حساب الأدمن من `config/admin.php` + `ADMIN_*` في `.env`.

---

## 18. قائمة التحقق قبل الإطلاق

- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` مُولَّد
- [ ] قاعدة بيانات MySQL + migrations
- [ ] `public/storage` symlink يعمل
- [ ] `storage/` و `bootstrap/cache/` قابلة للكتابة (775)
- [ ] `npm run build` ورفع `public/build`
- [ ] بريد SMTP يعمل + OTP يصل
- [ ] حذف أي سكربت deploy مؤقت بعد الاستخدام
- [ ] عدم رفع `.env` أو `database.sqlite` للمستودع

---

*لخطوات الرفع على Namecheap Shared Business بدون Terminal راجع `docs/NAMECHEAP_DEPLOYMENT.md`.*
