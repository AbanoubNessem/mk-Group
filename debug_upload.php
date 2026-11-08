<?php
/**
 * ملف لفحص مشاكل رفع الملفات
 * افتحه من المتصفح: http://localhost/eCommerce/debug_upload.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فحص مشاكل رفع الملفات</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>فحص إعدادات رفع الملفات</h1>
    
    <div class="box">
        <h2>إعدادات PHP:</h2>
        <ul>
            <li><strong>upload_max_filesize:</strong> <span class="<?php echo (ini_get('upload_max_filesize') >= '30M') ? 'success' : 'error'; ?>"><?php echo ini_get('upload_max_filesize'); ?></span></li>
            <li><strong>post_max_size:</strong> <span class="<?php echo (ini_get('post_max_size') >= '30M') ? 'success' : 'error'; ?>"><?php echo ini_get('post_max_size'); ?></span></li>
            <li><strong>max_file_uploads:</strong> <?php echo ini_get('max_file_uploads'); ?></li>
            <li><strong>memory_limit:</strong> <?php echo ini_get('memory_limit'); ?></li>
            <li><strong>max_execution_time:</strong> <?php echo ini_get('max_execution_time'); ?></li>
        </ul>
    </div>
    
    <div class="box">
        <h2>مجلدات التخزين:</h2>
        <ul>
            <li><strong>storage/app/public:</strong> 
                <span class="<?php echo is_dir(__DIR__ . '/storage/app/public') ? 'success' : 'error'; ?>">
                    <?php echo is_dir(__DIR__ . '/storage/app/public') ? 'موجود ✓' : 'غير موجود ✗'; ?>
                </span>
            </li>
            <li><strong>public/storage:</strong> 
                <span class="<?php echo is_link(__DIR__ . '/public/storage') || is_dir(__DIR__ . '/public/storage') ? 'success' : 'error'; ?>">
                    <?php echo (is_link(__DIR__ . '/public/storage') || is_dir(__DIR__ . '/public/storage')) ? 'موجود ✓' : 'غير موجود ✗'; ?>
                </span>
            </li>
            <li><strong>صلاحيات storage/app/public:</strong> 
                <?php 
                $storage_path = __DIR__ . '/storage/app/public';
                if (is_dir($storage_path)) {
                    $writable = is_writable($storage_path);
                    echo '<span class="' . ($writable ? 'success' : 'error') . '">' . ($writable ? 'قابل للكتابة ✓' : 'غير قابل للكتابة ✗') . '</span>';
                } else {
                    echo '<span class="error">المجلد غير موجود</span>';
                }
                ?>
            </li>
        </ul>
    </div>
    
    <div class="box">
        <h2>اختبار رفع ملف:</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="test_file" accept="image/*">
            <button type="submit">اختبار الرفع</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
            echo '<div class="box">';
            echo '<h3>نتيجة الاختبار:</h3>';
            
            if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
                echo '<p class="success">✓ تم استقبال الملف بنجاح!</p>';
                echo '<pre>';
                echo 'الاسم: ' . $_FILES['test_file']['name'] . "\n";
                echo 'الحجم: ' . ($_FILES['test_file']['size'] / 1024) . ' KB' . "\n";
                echo 'النوع: ' . $_FILES['test_file']['type'] . "\n";
                echo '</pre>';
            } else {
                echo '<p class="error">✗ فشل رفع الملف!</p>';
                $errors = [
                    UPLOAD_ERR_INI_SIZE => 'الملف أكبر من upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'الملف أكبر من MAX_FILE_SIZE في النموذج',
                    UPLOAD_ERR_PARTIAL => 'تم رفع جزء من الملف فقط',
                    UPLOAD_ERR_NO_FILE => 'لم يتم رفع أي ملف',
                    UPLOAD_ERR_NO_TMP_DIR => 'مجلد temp غير موجود',
                    UPLOAD_ERR_CANT_WRITE => 'فشل كتابة الملف على القرص',
                    UPLOAD_ERR_EXTENSION => 'تم إيقاف الرفع بواسطة extension',
                ];
                echo '<p class="error">الخطأ: ' . ($errors[$_FILES['test_file']['error']] ?? 'خطأ غير معروف') . '</p>';
            }
            echo '</div>';
        }
        ?>
    </div>
    
    <div class="box">
        <h2>الحلول المقترحة:</h2>
        <ol>
            <li>إذا كان <code>post_max_size</code> أقل من 30M، قم بتحديث <code>php.ini</code></li>
            <li>إذا كان مجلد <code>storage/app/public</code> غير قابل للكتابة، قم بتغيير الصلاحيات</li>
            <li>تأكد من وجود رابط symbolic في <code>public/storage</code></li>
            <li>تحقق من رسائل الخطأ في لوحة التحكم عند محاولة الإضافة</li>
        </ol>
    </div>
</body>
</html>



