# URL Shortener / QR Code Generator

Сервис для создания коротких ссылок с автоматической генерацией QR-кодов на Yii2.

## Возможности

- ✂️ Сокращение длинных URL
- 📱 Автоматическая генерация QR-кодов
- 📊 Статистика переходов
- 🔍 Проверка доступности URL перед созданием
- 📝 Логирование всех переходов (IP, User Agent, время)

## Технологии

- **Backend**: Yii2 Framework
- **Frontend**: Bootstrap 5 + jQuery
- **Database**: MySQL 8.0
- **HTTP Client**: yiisoft/yii2-httpclient
- **DevOps**: Docker + Docker Compose

## Быстрый старт

### Требования

- Docker
- Docker Compose

### Установка

1. Клонируйте репозиторий:
```bash
git clone <repository-url>
cd test
```

2. Запустите Docker контейнеры:
```bash
docker-compose up -d
```

3. Установите зависимости Composer:
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

## Docker сервисы

- **nginx** - веб-сервер (порт 80)
- **php** - PHP 8.1-FPM
- **mysql** - MySQL 8.0
- **phpmyadmin** - управление БД (порт 8080)

### Доступ к phpMyAdmin

```
URL: http://localhost:8080
User: root
Password: yii
```

## API

### Создание короткой ссылки

**POST** `/url/create`

```json
{
  "url": "https://example.com"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "short_url": "http://localhost/abc123",
    "qr_code": "data:image/png;base64,...",
    "original_url": "https://example.com",
    "short_code": "abc123",
    "hits": 0
  }
}
```

### Редирект по короткому коду

**GET** `/{code}`

Пример: `http://localhost/abc123` → редирект на оригинальный URL

### Статистика переходов

**GET** `/url/stats?code={code}`

```json
{
  "success": true,
  "data": {
    "short_code": "abc123",
    "short_url": "http://localhost/abc123",
    "original_url": "https://example.com",
    "hits": 5,
    "created_at": "2026-03-04 10:30:00",
    "logs": [
      {
        "ip": "127.0.0.1",
        "user_agent": "Mozilla/5.0...",
        "visited_at": "2026-03-04 10:31:00"
      }
    ]
  }
}
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

## Разработка

### Очистка кеша

```bash
docker exec yii_php bash -c "rm -rf /var/www/qr-project/runtime/cache/*"
```

### Создание новой миграции

```bash
docker exec yii_php bash -c "cd /var/www/qr-project && php yii migrate/create migration_name"
```

### Логи приложения

```bash
docker exec yii_php bash -c "tail -f /var/www/qr-project/runtime/logs/app.log"
```

### Остановка контейнеров

```bash
docker-compose down
```

### Полная очистка (включая volumes)

```bash
docker-compose down -v
```

## Конфигурация

### Изменение порта

В `compose.yaml` измените:
```yaml
nginx:
  ports:
    - "8000:80"  # вместо "80:80"
```

### Настройки БД

Отредактируйте `www/qr-project/config/db.php`:
```php
'dsn' => 'mysql:host=mysql;dbname=yii',
'username' => 'root',
'password' => 'yii',
```

## Производственное развертывание

1. Измените пароли в `compose.yaml` и `config/db.php`
2. Включите schema cache в `config/web.php`
3. Отключите debug режим в `web/index.php`
4. Настройте SSL/HTTPS в Nginx
5. Ограничьте доступ к phpMyAdmin

## Лицензия

BSD-3-Clause

## Автор

URL Shortener Service
