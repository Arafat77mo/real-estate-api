Skip to content
Navigation Menu
Arafat77mo
real-estate-api

Type / to search
Code
Issues
Pull requests
Actions
Projects
Wiki
Security
Insights
Settings
real-estate-api
/
README.md
in
main

Edit

Preview
Indent mode

Spaces
Indent size

4
Line wrap mode

Soft wrap
Editing README.md file contents
1
2
# API لإدارة العقارات

API قوية وقابلة للتوسع لإدارة العقارات. تم بناء هذه الواجهة باستخدام Laravel وتوفر ميزات لإدارة العقارات، تحميل الوسائط، إضافة الوظائف المتقدمة للبحث، إدارة المستخدمين، الأدوار، الإشعارات، والمهام.

## الميزات

- **إدارة العقارات**: 
   - إنشاء، تحديث، حذف، وعرض العقارات مع المواصفات التفصيلية مثل الموقع، السعر، الحجم، إلخ.
   - العمليات الأساسية لإدارة تفاصيل العقار.
   
- **إدارة الوسائط**: 
   - رفع وإدارة الصور والفيديوهات المرتبطة بالعقارات مثل صور العقار، صور الغلاف، والفيديوهات.
   
- **البحث المتقدم**: 
   - تصفية العقارات بناءً على معايير متعددة مثل الموقع، السعر، الحجم، وعدد الغرف.

- **الصفحات**: 
   - تصفية نتائج العقارات باستخدام `fastPaginate` لضمان الأداء مع كميات كبيرة من البيانات.

- **إدارة المستخدمين والأدوار**: 
   - إدارة المستخدمين مع أدوار مثل `المالك`، `المستخدم`.
   - تعيين الأدوار للمستخدمين وإدارة الصلاحيات للوصول إلى النظام.

- **الإشعارات**: 
   - إرسال إشعارات للمستخدمين مثل التنبيهات حول العقارات الجديدة، التحديثات، أو أي إجراءات أخرى.

- **المهام**: 
   - استخدام نظام الطوابير في Laravel لإدارة المهام في الخلفية مثل إرسال رسائل البريد الإلكتروني، معالجة الملفات، والمهام الأخرى.

- **واجهة RESTful**: 
   - تم تصميم الواجهة وفقًا لمبادئ REST لسهولة التكامل مع التطبيقات الأمامية والخدمات الخارجية.

## المتطلبات

- PHP >= 8.1
- Composer
- MySQL
- Laravel >= 10
- Node.js و npm (لتجميع الأصول)

## التثبيت

1. استنساخ المستودع:
   ```bash
   git clone git@github.com:Arafat77mo/real-estate-api.git


4. Copy the `.env.example` file to `.env` and configure your environment variables:
   ```bash
   cp .env.example .env
   ```

5. Generate an application key:
   ```bash
   php artisan key:generate
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

### API Endpoints

- **Properties**:
    - `GET /api/properties`: List all properties.
    - `POST /api/properties`: Create a new property.
    - `GET /api/properties/{id}`: View a specific property.
    - `PUT /api/properties/{id}`: Update a property.
    - `DELETE /api/properties/{id}`: Delete a property.


### Search Functionality

Use the `/api/properties` endpoint with query parameters to filter results:
- `location`: Filter by location.
- `min_price` and `max_price`: Filter by price range.
- `bedrooms`: Filter by number of bedrooms.

Example:
```bash
GET /api/properties?q=test
```

## Media Uploads

- Media files are stored in the `storage/app/public` directory.
- Run the following command to create a symbolic link to the `public` directory:
  ```bash
  php artisan storage:link
  ```

## Development

### Running Tests

Run the PHPUnit tests to ensure the application is working as expected:
```bash
php artisan test
```

### Compiling Assets

Compile the frontend assets using Laravel Mix:
```bash
npm run dev
```
For production:
```bash
npm run build
```

## Contributing

1. Fork the repository.
2. Create a new branch for your feature:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add a meaningful commit message"
   ```
4. Push to the branch:
   ```bash
   git push origin feature-name
   ```
5. Create a pull request.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

---

### Author

Developed by Muhammad Arafat.

Use Control + Shift + m to toggle the tab key moving focus. Alternatively, use esc then tab to move to the next interactive element on the page.
ّلم يتمّ اختيار أيّ ملفّ
Attach files by dragging & dropping, selecting or pasting them.
Editing real-estate-api/README.md at main · Arafat77mo/real-estate-api
Commit changes
There was an error committing your changes: Arafat77mo has committed since you started editing. See what changed
Commit message
Update README.md
Extended description
f
Direct commit or PR

Commit directly to the main branch

Create a new branch for this commit and start a pull request Learn more about pull requests
