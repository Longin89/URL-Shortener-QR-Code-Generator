<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для хранения коротких ссылок
 *
 * @property int $id
 * @property string $original_url
 * @property string $short_code
 * @property string $qr_code
 * @property int $hits
 * @property int $created_at
 * @property int $updated_at
 *
 * @property UrlLog[] $logs
 */
class Url extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%url}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['original_url', 'short_code'], 'required'],
            [['original_url'], 'string', 'max' => 255],
            [['short_code'], 'string', 'max' => 10],
            [['short_code'], 'unique'],
            [['qr_code'], 'string'],
            [['hits'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_url' => 'Оригинальный URL',
            'short_code' => 'Короткий код',
            'qr_code' => 'QR код',
            'hits' => 'Количество переходов',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * Связь с логами переходов
     * @return \yii\db\ActiveQuery
     */
    public function getLogs(): \yii\db\ActiveQuery
    {
        return $this->hasMany(UrlLog::class, ['url_id' => 'id']);
    }

    /**
     * Генерирует уникальный короткий код
     * @return string
     */
    public static function generateShortCode(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 6;
        
        do {
            $shortCode = '';
            for ($i = 0; $i < $length; $i++) {
                $shortCode .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
        } while (self::findOne(['short_code' => $shortCode]));
        
        return $shortCode;
    }

    /**
     * Генерирует QR код для ссылки
     * @param string $url
     * @return string base64 encoded image or direct URL
     */
    public static function generateQRCode(string $url): string
    {
        // Используем QR Server API для генерации QR кода
        $size = '200';
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($url);
        
        // Пробуем получить изображение и закодировать в base64
        $imageData = @file_get_contents($qrUrl);
        
        if ($imageData !== false && strlen($imageData) > 0) {
            return 'data:image/png;base64,' . base64_encode($imageData);
        }
        
        // Если не удалось, возвращаем прямую ссылку на API
        return $qrUrl;
    }

    /**
     * Увеличивает счетчик переходов
     */
    public function incrementHits()
    {
        $this->updateCounters(['hits' => 1]);
    }

    /**
     * Проверяет доступность URL
     * @param string $url
     * @return bool
     */
    public static function isUrlAccessible(string $url): bool
    {
        try {
            $client = new \yii\httpclient\Client([
                'requestConfig' => [
                    'options' => [
                        CURLOPT_TIMEOUT => 10,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ],
            ]);
            
            $response = $client->head($url)->send();
            
            return $response->isOk || ($response->statusCode >= 200 && $response->statusCode < 400);
        } catch (\Exception $e) {
            Yii::error('URL accessibility check failed: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * Получает полный короткий URL
     * @return string
     */
    public function getShortUrl(): string
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['url/redirect', 'code' => $this->short_code]);
    }
}
