#!/usr/bin/env php
<?php
/**
 * Скрипт для обновления QR-кодов существующих ссылок
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

echo "Обновление QR-кодов для всех ссылок...\n";

$urls = \app\models\Url::find()->all();
$count = 0;

foreach ($urls as $url) {
    // Генерируем QR-код для оригинального URL (не короткого)
    $url->qr_code = \app\models\Url::generateQRCode($url->original_url);
    
    if ($url->save(false)) {
        $count++;
        echo "✓ Обновлен QR-код для: {$url->original_url}\n";
    } else {
        echo "✗ Ошибка обновления для ID: {$url->id}\n";
    }
}

echo "\nГотово! Обновлено записей: {$count}\n";
