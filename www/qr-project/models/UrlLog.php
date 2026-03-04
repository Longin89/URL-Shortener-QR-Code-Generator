<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель для хранения логов переходов
 *
 * @property int $id
 * @property int $url_id
 * @property string $ip_address
 * @property string $user_agent
 * @property int $visited_at
 *
 * @property Url $url
 */
class UrlLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%url_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_id', 'ip_address', 'visited_at'], 'required'],
            [['url_id', 'visited_at'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 512],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Url::class, 'targetAttribute' => ['url_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_id' => 'URL ID',
            'ip_address' => 'IP адрес',
            'user_agent' => 'User Agent',
            'visited_at' => 'Дата посещения',
        ];
    }

    /**
     * Связь с URL
     * @return \yii\db\ActiveQuery
     */
    public function getUrl(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Url::class, ['id' => 'url_id']);
    }

    /**
     * Создает запись о переходе
     * @param int $urlId
     * @param string $ip
     * @param string $userAgent
     * @return bool
     */
    public static function logVisit(int $urlId, string $ip, string $userAgent = null): bool
    {
        $log = new self();
        $log->url_id = $urlId;
        $log->ip_address = $ip;
        $log->user_agent = $userAgent;
        $log->visited_at = time();
        
        return $log->save();
    }
}
