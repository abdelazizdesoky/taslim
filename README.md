<p align="center">
	<a href="https://laravel.com" target="_blank">
		<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
	</a>
</p>

# Taslim

مشروع إدارة فواتير مبني على Laravel — نسخة مُحسّنة من قوالب Laravel القياسية مع أدوات استيراد/تصدير، تقارير، ونظام صلاحيات للمسؤولين والمشاهدين.

**نظرة عامة:**
- **الهدف:** تطبيق لإدارة الفواتير والعملاء والمنتجات مع واجهة إدارة ومستخدمين وموظفين.
- **مبني على:** Laravel v10، PHP 8.x.

**المميزات الرئيسية:**
- إدارة الفواتير والمنتجات والعملاء.
- استيراد الفواتير عبر Excel وصدورها بتنسيق قابل للتنزيل.
- سجلات النشاط (Activity Log) والنسخ الاحتياطي اليدوي.
- نظام صلاحيات مبسّط للمسؤولين والموظفين والمشاهدين.

**المتطلبات:**
- PHP >= 8.0 (يوصى بـ PHP 8.4.x كما مستخدم محليًا).
- امتدادات PHP شائعة: `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`.
- MySQL أو MariaDB (أو SQLite للاختبارات حسب التهيئة).
- Composer و Node.js إذا أردت بناء الواجهات الأمامية.

**الملفات المهمة:**
- `app/` - كود التطبيق.
- `routes/` - تعريف المسارات (`web.php`, `auth.php`, `backend.php`, ...).
- `database/migrations/` - مهاجرات قاعدة البيانات.
- `tests/` - اختبارات Unit و Feature.

## التثبيت والتشغيل محليًا

1. انسخ المستودع:

```bash
git clone https://github.com/abdelazizdesoky/taslim.git
cd taslim
```

2. ثبّت الاعتمادات:

```bash
composer install
```

3. جهّز ملف البيئة:

Windows (PowerShell):
```powershell
copy .env.example .env
```

Unix/macOS:
```bash
cp .env.example .env
```

4. أنشئ مفتاح التطبيق:

```bash
php artisan key:generate
```

5. شغّل المهاجرات والـ seeders:

```bash
php artisan migrate --seed
```

6. شغّل الخادم المحلي:

```bash
php artisan serve
# ثم افتح http://127.0.0.1:8000
```

ملاحظة: إذا كنت تستخدم Laragon أو XAMPP يمكنك إعداد Virtual Host وتشغيل المشروع عبر خادم محلي مختلف.

## الاختبارات

تشغيل جميع الاختبارات:

```bash
php artisan test
```

اختبار ملف أو مجموعة محددة:

```bash
php artisan test --filter=AuthenticationTest
```

ملاحظة تقنية: يمكن تكوين `phpunit.xml` لاستخدام SQLite in-memory أو MySQL. إذا اخترت SQLite فتأكد من تمكين امتداد `pdo_sqlite` في PHP CLI.

## تنسيق الكود وفحص الجودة

- شغّل Laravel Pint لإصلاح تنسيق الكود:

```bash
vendor/bin/pint
```

- فحص الاعتمادات والثغرات:

```bash
composer outdated --direct
composer audit
```

## ملاحظات أمنية وتهيئة

- لا تضع ملف `.env` في المستودع. تحقق من أن `APP_DEBUG=false` في بيئة الإنتاج.
- تم نقل حزم التصحيح إلى `require-dev` (إن وُجدت) — تأكد أن حزم مثل debugbar لا تعمل في الإنتاج.

## مشكلة شائعة أثناء التشغيل المحلي

- إذا واجهت خطأ عند تشغيل الاختبارات بخصوص قاعدة `testing`، يمكنك:
	- إنشاء قاعدة MySQL باسم `testing` أو
	- ضبط `phpunit.xml` لاستخدام SQLite in-memory (وتفعيل امتداد `pdo_sqlite`).

## المساهمة

- افتح issue أو قدم Pull Request مع وصف واضح للتغيير.
- اتبع قواعد الكتابة ونمط المشروع، وشغّل `vendor/bin/pint` قبل رفع PR.

## رخصة

هذا المشروع يَستخدم رخصة MIT.

.
