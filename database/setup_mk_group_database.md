# إعداد قاعدة البيانات mk_group

## الخطوات المطلوبة:

### الطريقة الأولى: استخدام SQL Script (الأسهل)

1. افتح phpMyAdmin أو MySQL Command Line
2. تأكد من وجود قاعدة البيانات `ag_designss` وأنها تحتوي على جميع الجداول
3. قم بتشغيل ملف `create_mk_group_database.sql`:
   - في phpMyAdmin: اذهب إلى SQL tab وانسخ محتوى الملف
   - في Command Line: `mysql -u root -p < database/create_mk_group_database.sql`

### الطريقة الثانية: استخدام Laravel Migrations

1. قم بتحديث ملف `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mk_group
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. قم بإنشاء قاعدة البيانات يدوياً:
   ```sql
   CREATE DATABASE `mk_group` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. قم بتشغيل migrations:
   ```bash
   php artisan migrate
   ```

4. إذا كنت تريد نسخ البيانات من `ag_designss`:
   ```bash
   php artisan db:copy-from ag_designss
   ```

### الطريقة الثالثة: نسخ قاعدة البيانات كاملة

```bash
# Export from ag_designss
mysqldump -u root -p ag_designss > ag_designss_backup.sql

# Create new database
mysql -u root -p -e "CREATE DATABASE mk_group CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import to mk_group
mysql -u root -p mk_group < ag_designss_backup.sql
```

## بعد إعداد قاعدة البيانات:

1. قم بتحديث ملف `.env`:
   ```env
   DB_DATABASE=mk_group
   ```

2. قم بتشغيل:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. تحقق من الاتصال:
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

## ملاحظات:

- تأكد من أن قاعدة البيانات `ag_designss` موجودة وتحتوي على البيانات
- إذا كانت قاعدة البيانات فارغة، يمكنك استخدام migrations فقط
- احتفظ بنسخة احتياطية من قاعدة البيانات القديمة

