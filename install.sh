#!/bin/bash

# URL Shortener - Скрипт установки
echo "==================================="
echo "URL Shortener - Установка"
echo "==================================="

# Проверка Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker не установлен. Установите Docker и повторите попытку."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose не установлен. Установите Docker Compose и повторите попытку."
    exit 1
fi

echo "✅ Docker и Docker Compose обнаружены"

# Запуск контейнеров
echo ""
echo "📦 Запуск Docker контейнеров..."
docker-compose up -d

# Ожидание запуска MySQL
echo ""
echo "⏳ Ожидание запуска MySQL (15 секунд)..."
sleep 15

# Установка зависимостей Composer
echo ""
echo "📚 Установка зависимостей Composer..."
docker exec yii_php bash -c "cd /var/www/qr-project && composer install --no-dev --optimize-autoloader"

# Создание конфигурации БД (если не существует)
echo ""
echo "⚙️  Настройка конфигурации базы данных..."
docker exec yii_php bash -c "cd /var/www/qr-project/config && [ ! -f db.php ] && cp db.php.example db.php || echo 'db.php уже существует'"

# Применение миграций
echo ""
echo "🗄️  Применение миграций базы данных..."
docker exec yii_php bash -c "cd /var/www/qr-project && php yii migrate --interactive=0"

# Очистка кеша
echo ""
echo "🧹 Очистка кеша..."
docker exec yii_php bash -c "rm -rf /var/www/qr-project/runtime/cache/*"

# Установка прав
echo ""
echo "🔐 Настройка прав доступа..."
docker exec yii_php bash -c "chmod -R 777 /var/www/qr-project/runtime /var/www/qr-project/web/assets"

echo ""
echo "==================================="
echo "✅ Установка завершена!"
echo "==================================="
echo ""
echo "🌐 Приложение доступно по адресу:"
echo "   http://localhost"
echo ""
echo "🔧 phpMyAdmin доступен по адресу:"
echo "   http://localhost:8080"
echo "   User: root"
echo "   Password: yii"
echo ""
echo "📝 Для просмотра логов используйте:"
echo "   docker-compose logs -f"
echo ""
echo "🛑 Для остановки используйте:"
echo "   docker-compose down"
echo ""
