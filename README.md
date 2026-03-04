# URL Shortener / QR Code Generator

Сервис для создания коротких ссылок с автоматической генерацией QR-кодов на Yii2 (basic).

## Возможности

- ✂️ Сокращение длинных URL
- 📱 Автоматическая генерация QR-кодов
- 📊 Статистика переходов
- 🔍 Проверка доступности URL
- 📝 Логирование переходов (IP, User Agent, время)

### Проект состоит из:
- **Yii2**
- **Bootstrap 5 + jQuery**
- **MySQL 9.3**
- **Docker**

### Установка

1. Клонируйте репозиторий:
```bash
git clone https://github.com/Longin89/URL-Shortener-QR-Code-Generator
cd URL-Shortener-QR-Code-Generator-main
```

2. Собираем и поднимаем проект:
```bash
docker-compose build
```
```bash
docker-compose up -d
```

3. Устанавливаем зависимости Composer:
```bash
docker exec yii_php bash -c "cd /var/www/qr-project && composer install"
```

4. Создайте конфигурацию базы данных:
```bash
docker exec yii_php bash -c "cd /var/www/qr-project/config && cp db.php.example db.php"
```

5. Примените миграции:
```bash
docker exec yii_php bash -c "cd /var/www/qr-project && php yii migrate --interactive=0"
```

6. Откройте в браузере:
```
http://localhost
```

## Структура проекта

```
test/
├── compose.yaml           # Docker Compose конфигурация
├── php.ini               # Настройки PHP
├── my.cnf                # Настройки MySQL
├── vhost.conf            # Конфигурация Nginx
├── images/
│   └── php81fpm/         # Dockerfile для PHP
└── www/
    └── qr-project/       # Yii2 приложение
        ├── config/       # Конфигурация
        ├── controllers/  # Контроллеры
        ├── models/       # Модели
        ├── migrations/   # Миграции БД
        ├── views/        # Представления
        └── web/          # Публичная директория
```

### Доступ к phpMyAdmin

```
URL: http://localhost:8080
User: root
Password: yii
```

## База данных

### Таблица `url`
- `id` - ID записи
- `original_url` - оригинальный URL
- `short_code` - короткий код (6 символов)
- `qr_code` - QR-код в base64
- `hits` - количество переходов
- `created_at`, `updated_at` - временные метки

### Таблица `url_log`
- `id` - ID записи
- `url_id` - связь с `url`
- `ip_address` - IP посетителя
- `user_agent` - User Agent браузера
- `visited_at` - время перехода

### Настройки БД

Отредактируйте `www/qr-project/config/db.php`:
```php
'dsn' => 'mysql:host=mysql;dbname=yii',
'username' => 'root',
'password' => 'yii',
```