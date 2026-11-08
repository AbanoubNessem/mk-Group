<?php
/**
 * ملف للتحقق من إعدادات PHP المتعلقة برفع الملفات
 * قم بفتحه من المتصفح: http://localhost/eCommerce/check_php_settings.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات PHP - رفع الملفات</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .setting {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
            border-radius: 5px;
            border-right: 4px solid #667eea;
        }
        .setting-name {
            font-weight: bold;
            color: #555;
        }
        .setting-value {
            color: #333;
            font-family: monospace;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .success {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #17a2b8;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>إعدادات PHP - رفع الملفات</h1>
        
        <?php
        $upload_max = ini_get('upload_max_filesize');
        $post_max = ini_get('post_max_size');
        $max_execution = ini_get('max_execution_time');
        $max_input = ini_get('max_input_time');
        $memory_limit = ini_get('memory_limit');
        
        // تحويل إلى بايت للمقارنة
        function convertToBytes($val) {
            $val = trim($val);
            $last = strtolower($val[strlen($val)-1]);
            $val = (int)$val;
            switch($last) {
                case 'g': $val *= 1024;
                case 'm': $val *= 1024;
                case 'k': $val *= 1024;
            }
            return $val;
        }
        
        $upload_max_bytes = convertToBytes($upload_max);
        $post_max_bytes = convertToBytes($post_max);
        $code_max_bytes = 50 * 1024 * 1024; // 50MB
        
        $has_issues = false;
        ?>
        
        <div class="setting">
            <span class="setting-name">upload_max_filesize:</span>
            <span class="setting-value"><?php echo $upload_max; ?></span>
        </div>
        
        <div class="setting">
            <span class="setting-name">post_max_size:</span>
            <span class="setting-value"><?php echo $post_max; ?></span>
        </div>
        
        <div class="setting">
            <span class="setting-name">max_execution_time:</span>
            <span class="setting-value"><?php echo $max_execution; ?> ثانية</span>
        </div>
        
        <div class="setting">
            <span class="setting-name">max_input_time:</span>
            <span class="setting-value"><?php echo $max_input; ?> ثانية</span>
        </div>
        
        <div class="setting">
            <span class="setting-name">memory_limit:</span>
            <span class="setting-value"><?php echo $memory_limit; ?></span>
        </div>
        
        <?php if ($post_max_bytes < $code_max_bytes): ?>
            <div class="warning">
                <strong>⚠️ تحذير:</strong> 
                <p>قيمة <code>post_max_size</code> (<?php echo $post_max; ?>) أقل من الحد الأقصى المطلوب في الكود (50MB).</p>
                <p>يجب تحديث <code>php.ini</code> في XAMPP:</p>
                <pre>post_max_size = 100M
upload_max_filesize = 100M</pre>
            </div>
            <?php $has_issues = true; ?>
        <?php endif; ?>
        
        <?php if ($post_max_bytes < $upload_max_bytes): ?>
            <div class="warning">
                <strong>⚠️ تحذير:</strong> 
                <p>قيمة <code>post_max_size</code> يجب أن تكون أكبر من أو تساوي <code>upload_max_filesize</code></p>
            </div>
            <?php $has_issues = true; ?>
        <?php endif; ?>
        
        <?php if (!$has_issues): ?>
            <div class="success">
                <strong>✅ ممتاز!</strong> 
                <p>جميع الإعدادات صحيحة ويمكن رفع الملفات حتى 50MB.</p>
            </div>
        <?php endif; ?>
        
        <div class="info">
            <strong>ℹ️ معلومات:</strong>
            <ul>
                <li>الحد الأقصى في الكود: 50MB (51200 كيلوبايت) لكل صورة</li>
                <li>يمكن رفع صور متعددة، لكن مجموعها يجب أن يكون أقل من <code>post_max_size</code></li>
                <li>للتغييرات في <code>php.ini</code>، يجب إعادة تشغيل Apache</li>
            </ul>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            <p><strong>مسار php.ini:</strong> <?php echo php_ini_loaded_file(); ?></p>
            <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        </div>
    </div>
</body>
</html>



