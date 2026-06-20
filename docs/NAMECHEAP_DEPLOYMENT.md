# دليل رفع Elixira على Namecheap Shared Business (بدون Terminal)

> خطة **Shared Business** — لا يوجد SSH/Terminal  
> الهدف: تشغيل Laravel 12 + ظهور الصور المرفوعة بشكل صحيح

---

## 1. كيف يعمل تخزين الصور في المشروع

Laravel يحفظ الصور هكذا:

```
المسار الفعلي على السيرفر:
/home/USERNAME/elixira/storage/app/public/items/photo.jpg

مسار العرض في المتصفح:
https://yourdomain.com/storage/items/photo.jpg
```

### مجلدات الرفع في المشروع

| المحتوى | يُحفظ في | يظهر في URL |
|---------|---------|-------------|
| صورة منتج | `storage/app/public/items/` | `/storage/items/...` |
| معرض منتج | `storage/app/public/items/gallery/` | `/storage/items/gallery/...` |
| صورة باكيدج | `storage/app/public/packages/` | `/storage/packages/...` |
| شعار علامة | `storage/app/public/brands/` | `/storage/brands/...` |
| أفاتار مستخدم | `storage/app/public/users/avatars/` | `/storage/users/avatars/...` |
| صورة تقييم | `storage/app/public/ratings/` | `/storage/ratings/...` |

### الإعداد المطلوب في `.env`

```env
APP_URL=https://yourdomain.com
FILESYSTEM_DISK=public
```

> **لا تغيّر** مسارات الرفع في الكود — المشروع مضبوط على `store(..., 'public')` و `asset('storage/...')` وهذا هو المعيار الصحيح لـ Namecheap.

---

## 2. هيكل المجلدات على Namecheap (الطريقة الموصى بها)

```
/home/USERNAME/
├── elixira/                    ← المشروع كامل (خارج public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/                 ← Document Root يشير هنا
│   │   ├── index.php
│   │   ├── .htaccess
│   │   ├── build/              ← من npm run build
│   │   └── storage/            ← SYMLINK (مهم جداً)
│   ├── storage/
│   │   └── app/
│   │       └── public/         ← الصور المرفوعة هنا
│   ├── vendor/
│   └── .env
└── (لا تضع المشروع داخل public_html مباشرة)
```

### لماذا Document Root = `elixira/public`؟

- يحمي `.env` و `vendor/` من الوصول المباشر
- Laravel يعمل عبر `public/index.php`

---

## 3. خطوات الرفع — بالترتيب

### المرحلة أ: التحضير على جهازك (قبل الرفع)

```bash
# 1. تثبيت الاعتماديات
composer install --optimize-autoloader --no-dev

# 2. بناء الواجهة
npm ci
npm run build

# 3. توليد مفتاح التطبيق (انسخه لـ .env على السيرفر)
php artisan key:generate --show

# 4. تشغيل migrations على قاعدة بيانات محلية ثم تصدير SQL
php artisan migrate --force
# أو صدّر من phpMyAdmin محلياً
```

**ارفع هذه المجلدات/الملفات عبر FTP أو File Manager:**
- كل المشروع **ما عدا**: `.git`, `node_modules`, `tests`, `.env` (أنشئه على السيرفر)
- تأكد من رفع: `vendor/`, `public/build/`

---

### المرحلة ب: قاعدة البيانات في cPanel

1. **cPanel → MySQL Databases**
2. أنشئ Database + User + اربطهما (ALL PRIVILEGES)
3. **phpMyAdmin → Import** — ارفع ملف `.sql` من migrations
4. أو استخدم سكربت migrate لمرة واحدة (القسم 8)

---

### المرحلة ج: ملف `.env` على السيرفر

أنشئ `/home/USERNAME/elixira/.env` من `.env.example`:

```env
APP_NAME=Elixira
APP_ENV=production
APP_KEY=base64:XXXXX
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanel_dbname
DB_USERNAME=cpanel_dbuser
DB_PASSWORD=your_db_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

LOG_CHANNEL=stack
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
COMPANY_EMAIL=your@gmail.com

ADMIN_EMAIL=your@gmail.com
ADMIN_PASSWORD=StrongPasswordHere
ADMIN_NAME="Elixira Admin"

OTP_TTL_MINUTES=5
```

> `DB_HOST=localhost` هو الصحيح على Namecheap في أغلب الحالات.

---

### المرحلة د: ضبط Document Root

1. **cPanel → Domains** (أو Domain Manager)
2. اختر الدومين → **Document Root**
3. عيّنه إلى: `/home/USERNAME/elixira/public`
4. احفظ وانتظر 5–15 دقيقة

---

### المرحلة هـ: صلاحيات المجلدات (File Manager)

| المجلد | الصلاحية |
|--------|----------|
| `storage/` | **775** (recursive) |
| `storage/app/public/` | **775** |
| `bootstrap/cache/` | **775** |

بدون صلاحيات الكتابة: الصور لن تُرفع والجلسات قد تفشل.

---

## 4. ربط الصور — Storage Symlink (الأهم)

Laravel يحتاج:

```
public/storage  →  ../storage/app/public
```

بدون هذا الرابط: **الصور تُرفع لكن لا تظهر** (404 على `/storage/...`).

### الطريقة 1: cPanel File Manager (إن وُجد خيار Link)

1. افتح `elixira/public/`
2. ابحث عن **+ Link** أو **Create Symbolic Link**
3. الرابط: `storage`
4. الهدف: `../storage/app/public`

### الطريقة 2: سكربت PHP لمرة واحدة (بدون Terminal)

1. أنشئ ملف `elixira/public/link-storage-once.php`:

```php
<?php
declare(strict_types=1);

$secret = 'CHANGE_ME_TO_RANDOM_STRING'; // غيّر هذا قبل الرفع
if (($_GET['key'] ?? '') !== $secret) {
    http_response_code(403);
    exit('Forbidden');
}

$target = realpath(__DIR__.'/../storage/app/public');
$link = __DIR__.'/storage';

if ($target === false) {
    exit('Target folder missing. Create storage/app/public first.');
}

if (file_exists($link)) {
    exit(is_link($link) ? 'Symlink already exists.' : 'A real storage folder/file exists. Remove it first.');
}

if (symlink($target, $link)) {
    exit('SUCCESS: storage symlink created.');
}

exit('FAILED: could not create symlink. Contact Namecheap support or use Method 3.');
```

2. افتح في المتصفح:
   `https://yourdomain.com/link-storage-once.php?key=CHANGE_ME_TO_RANDOM_STRING`
3. عند ظهور `SUCCESS` → **احذف الملف فوراً**
4. اختبر: `https://yourdomain.com/storage/` (قد يعرض 403 وهذا طبيعي)

### الطريقة 3: نسخ يدوي (حل أخير — غير مثالي)

إذا فشل symlink تماماً:

1. انسخ محتويات `storage/app/public/` إلى `public/storage/`
2. **عيب:** كل رفع جديد يحتاج نسخ يدوي — حاول الطريقة 1 أو 2 أولاً

---

## 5. التحقق من الصور بعد الرفع

### اختبار 1: صورة ثابتة
ارفع ملف `test.jpg` إلى `storage/app/public/`  
افتح: `https://yourdomain.com/storage/test.jpg`  
- ✅ تظهر = symlink صحيح  
- ❌ 404 = أعد خطوة الربط

### اختبار 2: من لوحة الأدمن
1. سجّل دخول Admin
2. أضف منتج بصورة
3. افتح صفحة المنتج في المتجر — يجب أن تظهر الصورة

### مسارات Blade في المشروع (للمراجعة)

```blade
{{ asset('storage/' . $item->image) }}
{{ asset('storage/' . $package->image) }}
{{ asset('storage/' . $brand->logo) }}
```

---

## 6. إعدادات PHP في cPanel

**cPanel → Select PHP Version / MultiPHP INI Editor:**

| الإعداد | القيمة الموصى بها |
|---------|-------------------|
| `memory_limit` | `256M` أو `512M` |
| `upload_max_filesize` | `20M` |
| `post_max_size` | `25M` |
| `max_execution_time` | `120` |
| PHP Version | **8.2** أو **8.3** |

---

## 7. Cron Jobs (بدون Terminal)

**cPanel → Cron Jobs**

| التوقيت | الأمر |
|---------|--------|
| يومياً 08:00 | `cd /home/USERNAME/elixira && /usr/local/bin/php artisan vendors:process-subscriptions` |
| أول الشهر 00:05 | `cd /home/USERNAME/elixira && /usr/local/bin/php artisan points:reset-monthly` |

> مسار PHP قد يختلف — تحقق من **cPanel → PHP Info** (غالباً `/usr/local/bin/php` أو `/opt/cpanel/ea-php82/root/usr/bin/php`).

---

## 8. تشغيل Migrations بدون Terminal (اختياري)

إذا لم تصدّر SQL جاهزاً، أنشئ مؤقتاً `elixira/public/migrate-once.php`:

```php
<?php
declare(strict_types=1);

$secret = 'CHANGE_ME_MIGRATE_KEY';
if (($_GET['key'] ?? '') !== $secret) {
    http_response_code(403);
    exit('Forbidden');
}

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
echo nl2br(e(Illuminate\Support\Facades\Artisan::output()));
echo '<br>DELETE THIS FILE NOW.';
```

**احذف الملف فوراً بعد التشغيل.**

---

## 9. Cache للإنتاج (مرة واحدة)

بعد ضبط `.env`، نفّذ عبر cron لمرة واحدة أو سكربت مشابه:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 10. SSL (HTTPS)

1. **cPanel → SSL/TLS Status**
2. فعّل **AutoSSL** (Let's Encrypt مجاني)
3. تأكد أن `APP_URL=https://...` في `.env`

---

## 11. أخطاء شائعة وحلولها

| المشكلة | السبب | الحل |
|---------|--------|------|
| صفحة بيضاء 500 | `.env` ناقص أو صلاحيات | راجع `storage/logs/laravel.log` |
| الصور لا تظهر | لا symlink | القسم 4 |
| 419 عند التسجيل | جلسة/CSRF | `SESSION_DRIVER=file` + صلاحيات `storage/framework/sessions` |
| CSS مكسور | لم يُرفع `public/build` | `npm run build` محلياً ثم ارفع |
| `APP_URL` خاطئ | http بدل https | صحّح `.env` ثم `config:cache` |
| OTP لا يصل | Gmail | App Password + `MAIL_ENCRYPTION=tls` |

---

## 12. أمان ما بعد الرفع

- [ ] `APP_DEBUG=false`
- [ ] حذف `link-storage-once.php` و `migrate-once.php`
- [ ] `.env` خارج `public/` (Document Root = public فقط)
- [ ] كلمة مرور أدمن قوية في `.env`
- [ ] لا ترفع `database.sqlite` أو ملفات log

---

## 13. ملخص مسار الصور على Namecheap

```
رفع من لوحة التحكم
       ↓
storage/app/public/items/xyz.jpg    (ملف حقيقي)
       ↓
public/storage → symlink
       ↓
https://yourdomain.com/storage/items/xyz.jpg    (ما يراه الزائر)
```

**هذا المسار مطابق 100% لما يتوقعه Laravel و Namecheap Shared Hosting.**

---

## 14. الدعم

- سجلات الأخطاء: `storage/logs/laravel.log`
- تقرير الميزات الكامل: `docs/IMPLEMENTATION_REPORT.md`
- اختبارات محلية قبل كل رفع: `php artisan test`

---

*آخر تحديث: يونيو 2026*
