<?php

use yii\db\Migration;

/**
 * Миграция для создания таблиц сервиса коротких ссылок
 */
class m240303_000000_create_url_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Таблица для хранения коротких ссылок
        $this->createTable('{{%url}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->string(2048)->notNull()->comment('Оригинальный URL'),
            'short_code' => $this->string(10)->notNull()->unique()->comment('Короткий код'),
            'qr_code' => $this->text()->comment('QR код в base64'),
            'hits' => $this->integer()->defaultValue(0)->comment('Количество переходов'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        // Индекс для быстрого поиска по короткому коду
        $this->createIndex(
            'idx-url-short_code',
            '{{%url}}',
            'short_code'
        );

        // Таблица для логов переходов
        $this->createTable('{{%url_log}}', [
            'id' => $this->primaryKey(),
            'url_id' => $this->integer()->notNull()->comment('ID короткой ссылки'),
            'ip_address' => $this->string(45)->notNull()->comment('IP адрес посетителя'),
            'user_agent' => $this->string(512)->comment('User Agent'),
            'visited_at' => $this->integer()->notNull()->comment('Дата перехода'),
        ]);

        // Внешний ключ
        $this->addForeignKey(
            'fk-url_log-url_id',
            '{{%url_log}}',
            'url_id',
            '{{%url}}',
            'id',
            'CASCADE'
        );

        // Индексы для логов
        $this->createIndex(
            'idx-url_log-url_id',
            '{{%url_log}}',
            'url_id'
        );

        $this->createIndex(
            'idx-url_log-visited_at',
            '{{%url_log}}',
            'visited_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-url_log-url_id', '{{%url_log}}');
        $this->dropTable('{{%url_log}}');
        $this->dropTable('{{%url}}');
    }
}
