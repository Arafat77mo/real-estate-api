

# Real Estate API 

API خلفي قوي وقابل للتوسع لإدارة العقارات.  
تم بناء هذه الـ API باستخدام Laravel، وتوفر ميزات لإدارة العقارات، تحميل الوسائط، إدارة المستخدمين، توفير الأدوار والصلاحيات، الإشعارات، ومعالجة المهام في الخلفية.

---

## **الميزات**

- **إدارة العقارات**
    - عمليات CRUD (إنشاء، قراءة، تحديث، حذف) لإدارة العقارات مع تفاصيل مثل الموقع، السعر، الحجم، والميزات.

- **إدارة الوسائط**
    - رفع وإدارة الصور والفيديوهات المرتبطة بالعقارات (صور العقارات، الصور الرئيسية، والفيديوهات).

- **البحث المتقدم والفلاتر**
    - فلترة العقارات استنادًا إلى معايير متعددة مثل الموقع، النطاق السعري، الحجم، وعدد غرف النوم.

- **التصفية باستخدام Pagination**
    - استخدام `fastPaginate()` للتعامل مع مجموعات بيانات كبيرة بكفاءة.

- **إدارة المستخدمين، الأدوار والصلاحيات**
    - إدارة المستخدمين مع أدوار مثل `مالك`، `مستخدم`، و`مشرف`.
    - تخصيص الأدوار وإدارة الصلاحيات للمستخدمين وفقًا للأدوار المختلفة.

- **الإشعارات**
    - إرسال إشعارات للمستخدمين حول العقارات الجديدة، التحديثات، أو الإجراءات التي تمت (مثل الشراء، الإيجار، أو التقسيط).
    - دعم الإشعارات في الوقت الحقيقي (real-time) باستخدام قنوات البث (Broadcasting).

- **المهام في الخلفية (Jobs)**
    - استخدام نظام الطوابير (Queues) في Laravel لمعالجة المهام الثقيلة مثل إرسال البريد الإلكتروني، معالجة الملفات، وغيرها من العمليات.

- **استخدام Redis للتخزين المؤقت**
    - تحسين الأداء عبر تخزين بيانات العقارات وعمليات البحث في Redis لتقليل استعلامات قاعدة البيانات.

- **تصميم API وفقًا لمبادئ RESTful**
    - توفير واجهة موحدة وسهلة التكامل مع التطبيقات الأمامية وخدمات الطرف الثالث.

- **Redis للتخزين المؤقت:**
    - تم استخدام Redis لتخزين نتائج الاستعلامات المكثفة مثل قائمة العقارات وبيانات التقارير، مما يُحسن من أداء التطبيق ويقلل من زمن الاستعلامات.

- **إدارة المستخدمين والأدوار والصلاحيات:**
    - تمت إضافة واجهات لإدارة المستخدمين مع تعيين الأدوار (مثل `مالك`، `مستخدم`، `مشرف`) وتنفيذ صلاحيات الوصول عبر Laravel Gates.

- **الإشعارات:**
    - تم تطوير نظام الإشعارات لإعلام المستخدمين والـ Agents بعمليات الشراء، الإيجار، والتقسيط في الوقت الحقيقي، باستخدام الإشعارات عبر قاعدة البيانات والبث (Broadcast).

- **المهام في الخلفية (Jobs):**
    - تم دمج نظام الطوابير (Queues) لمعالجة المهام الثقيلة مثل إرسال البريد الإلكتروني ومعالجة الملفات، مما يُحسن من سرعة استجابة التطبيق.

- **تحسين البحث والتصفية:**
    - تم استخدام `fastPaginate()` مع تحسينات إضافية في الفلاتر لتقديم تجربة بحث متقدمة وسريعة.

- **تصميم RESTful API:**
    - تم تصميم الـ API وفقًا لمبادئ REST لتوفير واجهة متسقة وسهلة التكامل مع التطبيقات الأمامية وخدمات الطرف الثالث.

---
## **تسجيل الدخول عبر وسائل التواصل الاجتماعي**

يتيح النظام إمكانية تسجيل الدخول باستخدام وسائل التواصل الاجتماعي مثل **Google**، **Facebook**، و**GitHub** باستخدام [Laravel Socialite](https://laravel.com/docs/socialite).

### **كيفية الإعداد**

1. **تثبيت Laravel Socialite:**
   ```bash
   composer require laravel/socialite
   ```

2. **تهيئة إعدادات المزودين:**

   في ملف `config/services.php`، أضف الإعدادات الخاصة بكل مزود:
   ```php
   'google' => [
       'client_id' => env('GOOGLE_CLIENT_ID'),
       'client_secret' => env('GOOGLE_CLIENT_SECRET'),
       'redirect' => env('GOOGLE_REDIRECT_URI'),
   ],

   'facebook' => [
       'client_id' => env('FACEBOOK_CLIENT_ID'),
       'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
       'redirect' => env('FACEBOOK_REDIRECT_URI'),
   ],

   'github' => [
       'client_id' => env('GITHUB_CLIENT_ID'),
       'client_secret' => env('GITHUB_CLIENT_SECRET'),
       'redirect' => env('GITHUB_REDIRECT_URI'),
   ],
   ```
   تأكد من إعداد المتغيرات البيئية المناسبة في ملف `.env`.


### **تجربة التسجيل الاجتماعي**

- بعد إكمال الإعدادات، شغل الخادم باستخدام:
  ```bash
  php artisan serve
  ```
- جرب زيارة الرابط:
  ```
  http://localhost:8000/auth/social/google/redirect
  ```
  ويمكنك تغيير `google` إلى `facebook` أو `github` حسب المزود الذي ترغب بتجربته.
- بعد تسجيل الدخول بنجاح، سيقوم النظام بإنشاء مستخدم جديد (إن لم يكن موجودًا) وتوليد توكن (Token) يتم إرجاعه في استجابة JSON لاستخدامه في باقي نقاط النهاية المحمية.

---

## **المتطلبات**

- PHP >= 8.2
- Composer
- MySQL
- Laravel >= 11
- Redis (للتخزين المؤقت)
- Node.js & npm (لتجميع الأصول الأمامية)

---

## **طريقة التثبيت**

1. **استنساخ الريبو:**
   ```bash
   git clone git@github.com:Arafat77mo/real-estate-api.git
   cd real-estate-api
   ```

2. **تثبيت الاعتمادات:**
   ```bash
   composer install
   npm install
   ```

3. **إعداد متغيرات البيئة:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **إعداد قاعدة البيانات وتشغيل التراكيب (Migrations):**
   ```bash
   php artisan migrate --seed
   ```

5. **بدء الخادم للتطوير:**
   ```bash
   php artisan serve
   ```

---

## **تشغيل الاختبارات**

تشغيل اختبارات PHPUnit للتأكد من عمل التطبيق بالشكل المطلوب:
```bash
php artisan test
```

---

## **تجميع الأصول**

- للتطوير:
  ```bash
  npm run dev
  ```
- للإنتاج:
  ```bash
  npm run build
  ```

---

## **المساهمة**

1. **Fork للمستودع**
2. **إنشاء فرع جديد:**
   ```bash
   git checkout -b feature-name
   ```
3. **تنفيذ التغييرات:**
   ```bash
   git commit -m "إضافة وصف الميزة"
   ```
4. **دفع الفرع:**
   ```bash
   git push origin feature-name
   ```
5. **إنشاء Pull Request**

---

## **الرخصة**

هذا المشروع مرخص تحت رخصة **MIT**. راجع ملف LICENSE للمزيد من التفاصيل.

---

## **المؤلف**

تم تطويره بواسطة **Muhammad Arafat**.

---




