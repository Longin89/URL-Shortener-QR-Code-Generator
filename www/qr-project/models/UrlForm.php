<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Форма для создания короткой ссылки
 */
class UrlForm extends Model
{
    public $url;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['url', 'required', 'message' => 'Введите URL'],
            ['url', 'url', 'message' => 'Введите корректный URL'],
            ['url', 'string', 'max' => 255],
            ['url', 'validateUrlScheme'],
            ['url', 'validateUrlAccessibility'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'url' => 'URL',
        ];
    }

    /**
     * Проверяет http или https
     */
    public function validateUrlScheme(string $attribute): void
    {
        if (!preg_match('/^https?:\/\/.+/i', $this->$attribute)) {
            $this->addError($attribute, 'URL должен начинаться с http:// или https://');
        }
    }

    /**
     * Проверяет доступность URL
     */
    public function validateUrlAccessibility(string $attribute): void
    {
        if (!$this->hasErrors()) {
            if (!Url::isUrlAccessible($this->$attribute)) {
                $this->addError($attribute, 'Данный URL не доступен');
            }
        }
    }

    /**
     * Создает короткую ссылку
     * @return Url|null
     */
    public function createShortUrl(): ? Url
    {
        if (!$this->validate()) {
            return null;
        }

        // Проверяем, есть ли такой URL в базе
        $existingUrl = Url::findOne(['original_url' => $this->url]);
        if ($existingUrl) {
            return $existingUrl;
        }

        // Создаем новую короткую ссылку
        $url = new Url();
        $url->original_url = $this->url;
        $url->short_code = Url::generateShortCode();
        
        // Генерируем QR
        $url->qr_code = Url::generateQRCode($this->url);
        
        if ($url->save()) {
            return $url;
        }

        return null;
    }
}
