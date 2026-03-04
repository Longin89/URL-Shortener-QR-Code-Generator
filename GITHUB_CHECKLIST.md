# Чек-лист для публикации на GitHub

## ✅ Файлы для Git

### Обязательные файлы (должны быть в репозитории):
- [x] README.md - документация проекта
- [x] LICENSE - лицензия
- [x] .gitignore - игнорирование файлов
- [x] .gitattributes - настройки Git
- [x] install.sh - скрипт установки

### Docker конфигурация:
- [x] compose.yaml - Docker Compose конфигурация
- [x] images/php81fpm/Dockerfile - образ PHP
- [x] php.ini - настройки PHP
- [x] my.cnf - настройки MySQL
- [x] vhost.conf - конфигурация Nginx

### Yii2 приложение:
- [x] composer.json - зависимости PHP
- [x] config/*.php (кроме db.php) - конфигурация
- [x] config/db.php.example - пример настроек БД
- [x] migrations/*.php - миграции базы данных
- [x] models/*.php - модели
- [x] controllers/*.php - контроллеры
- [x] views/**/*.php - представления
- [x] web/js/*.js - JavaScript
- [x] web/css/*.css - стили
- [x] web/index.php - точка входа

### Игнорируемые директории/файлы:
- [x] mysql-data/ - данные MySQL (локальные)
- [x] www/.bash_history - история команд
- [x] www/.composer/ - кеш Composer
- [x] vendor/ - зависимости PHP (устанавливаются через composer install)
- [x] runtime/* - кеш и логи (создаются автоматически)
- [x] web/assets/* - скомпилированные ассеты
- [x] config/db.php - конфигурация БД с паролями

## 📋 Перед публикацией

### 1. Проверьте чувствительные данные:
```bash
# Проверьте, что пароли не попадут в репозиторий
grep -r "password" www/qr-project/config/ --include="*.php" --exclude="db.php.example"
```

### 2. Проверьте структуру Git:
```bash
cd /home/longin/Рабочий\ стол/test
git status
git add .
git status
```

### 3. Первый коммит:
```bash
git commit -m "Initial commit: URL Shortener with QR Code Generator"
```

### 4. Создайте репозиторий на GitHub:
- Зайдите на https://github.com
- Нажмите "New repository"
- Название: url-shortener (или другое)
- Описание: URL Shortener with QR Code Generator built on Yii2
- Public или Private
- НЕ создавайте README, .gitignore, LICENSE (они уже есть)

### 5. Подключите удаленный репозиторий:
```bash
git remote add origin https://github.com/USERNAME/REPOSITORY.git
git branch -M main
git push -u origin main
```

## 🔍 Финальная проверка

### Тест на чистой системе:
```bash
# Клонируйте репозиторий
git clone https://github.com/USERNAME/REPOSITORY.git
cd REPOSITORY

# Запустите установку
./install.sh

# Проверьте работу
curl http://localhost
```

### Проверьте README:
- [ ] Описание проекта понятно
- [ ] Инструкции по установке работают
- [ ] API документация актуальна
- [ ] Скриншоты добавлены (опционально)

### Проверьте .gitignore:
- [ ] vendor/ игнорируется
- [ ] runtime/* игнорируется
- [ ] config/db.php игнорируется
- [ ] mysql-data/ игнорируется

## 🚀 После публикации

### Добавьте темы (topics) на GitHub:
- yii2
- url-shortener
- qr-code
- docker
- php
- mysql

### Добавьте badges в README (опционально):
```markdown
![PHP Version](https://img.shields.io/badge/PHP-8.1-blue)
![Yii2 Version](https://img.shields.io/badge/Yii2-2.0-green)
![Docker](https://img.shields.io/badge/Docker-Ready-blue)
```

### Настройте GitHub Pages (если нужно):
- Settings → Pages
- Source: Deploy from branch
- Branch: main / docs

## ✅ Готово к публикации!

Проект готов к загрузке на GitHub. Все необходимые файлы включены, чувствительные данные защищены.
