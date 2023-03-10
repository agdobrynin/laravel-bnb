# Учебный проект - аренда жилья.

**Стек:**
- 🐘 Php 8.2 + Laravel 9
- 🥉 Vue 3 by composition api + TypeScript + Pug + Pinia
- 🧶 Boostrap css 5
- 🦖 MariaDb

### Установка проекта

Для развертывания проекта потребуется установленный
🐳 **docker** или же 🐋 **docker desktop** проект будет работать
как на Windows с поддержкой WSL2 так и на Linux машине.

Локальная разработка и тестирование проекта использует
легковесный [Laravel Sail](https://laravel.com/docs/9.x/sail)
для работы с docker контейнерами.

#### Настройка переменных окружения проекта

Создать файл настроект проекта

```shell
cp .env.example .env
```

и если нужно настроить переменные окружения в `.env` файле

#### Утановка зависимостей проекта через composer

Если на машине разработчика **не установлен** локально composer
то зависимости проекта можно установить так

```shell
docker run --rm --interactive --tty \
  -u "$(id -u):$(id -g)" \
  --volume $PWD:/app \
  composer install
```

⚠ если же на машине разработчика установлен **composer** и **php**
то для начала необходимо установить зависимости
проекта выполнив команду

```shell
composer install --ignore-platform-reqs --no-scripts
```

на этом подготовка к работе с Laravel Sail закончен.

### Запуск проекта
Поднять docker контейнеры с помощтю Laravel Sail
```shell
./vendor/bin/sail up -d
```
```shell
./vendor/bin/sail composer install
```
доступные команды по остановке или пересборке контейнеров можно узнать на странице
[Laravel Sail](https://laravel.com/docs/9.x/sail)
или выполните команду `./vendor/bin/sail` для получения краткой справки о доступных командах.

1.  Сгенерировать application key
    ```shell
    ./vendor/bin/sail artisan key:generate
    ```

2.  Выполинть миграции и заполинть таблицы тестовыми данными
    ```shell
    ./vendor/bin/sail artisan migrate --seed
    ```
3. Собрать фронт 
    ```shell
    ./vendor/bin/sail npm install
    ```
    ```shell
    ./vendor/bin/sail npm run build
    ```


### Доступные сайты в dev окружении

|         Host          | Назначение                                                                     |
|:---------------------:|:-------------------------------------------------------------------------------|
|   http://localhost    | сайт приложения                                                                |
| http://localhost:8080 | Adminer - вэб интерфейс к базе. Логин, пароль и имя базы взять из `.env` файла |
| http://localhost:8025 | Mailpit - вэб интерфейс для отладки отправки email сообщения                   |
