# Учебный проект - аренда жилья (Fullstack - Laravel & Vue3).
🎫 [Сертификат об успешном прохождении курса.](https://www.udemy.com/certificate/UC-005208ce-5326-4bb4-a17b-4f5c3128b280/)

Создано SPA приложение "Анернда" с возможностью
регистарции пользователя и верификации аккаунта чрезе
email сообщение.

Добавлена возможность управление аккаунтом пользователя - смена email адреса, имени, смена пароля,
сброс и смена забытого пароля, ссылка подтверждения email адреса.

Для страницы просмотра предложений аренды разработана система
фильтарции предложений и пагинации (постраничный вывод).

Для сделанных бронирований пользоватля или анонимного пользователя предустмотерна возжноноть оставить отзыв 
с выставлнием рейтинга адрендуемого жилья.

Разработана система оповещения через email о возможности оставить отзыв о бронированиии арендуемом жилье. 

**Стек:**
- 🐘 Php 8.2 + Laravel 9 (пакет Fortify для авторизации и регистрации пользователя)
- 🥉 Vue 3 by composition api + TypeScript + Pug + Pinia
- 🧶 Boostrap css 5
- 🦖 MariaDb
- 📗 Swagger php, Swagger UI - документация к API 
- ⛑ Phpunit - тестирование.
- 🐋 Docker, Docker compose, Laravel Sail - для локальной разработки.

-------
Темы изученые в курсе

In Laravel:

- Routing, Routing api resource, Middlewares
- Eloquent ORM
- Models, Models event, Migrations
- Seeding databases with fake data
- Database relations - creating, managing
- _Laravel Fortify_: authentication & authorization - registering users, signing in, user verification, limiting access
- Sending emails, and testing emails locally using Mailtip!
- Pagination & Filtering for collection with "query scope"
- Input data validation from SPA forms

In Vue:

- Composition API
- Reactive data
- Computed properties
- Use composable function
- Passing data using props
- Emitting custom events
- Making requests with axios
- Components & component slots
- Creating layouts
- Use Pinia as data **store** 
- Form data validation

### Установка проекта

Для развертывания проекта потребуется установленный
🐳 **docker** или же 🐋 **docker desktop** проект будет работать
как на Windows с поддержкой WSL2 так и на Linux машине.

Локальная разработка и тестирование проекта использует
легковесный [Laravel Sail](https://laravel.com/docs/9.x/sail)
для работы с docker контейнерами.

#### Сборка образов докера

Настроить переменные окружения (если требуется изменить их):

```shell
cp .env.example .env
```

Собрать контейнеры docker:

```shell
docker run --rm -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

на этом подготовка к работе с Laravel Sail закончен.

### Запуск проекта
Поднять docker контейнеры с помощтю Laravel Sail
```shell
./vendor/bin/sail up -d
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

|                Host                | Назначение                                                                     |
|:----------------------------------:|:-------------------------------------------------------------------------------|
|          http://localhost          | сайт приложения                                                                |
| http://localhost/api/documentation | Документация на API - swagger-php, swagger UI                                  |
|       http://localhost:8080        | Adminer - вэб интерфейс к базе. Логин, пароль и имя базы взять из `.env` файла |
|       http://localhost:8025        | Mailpit - вэб интерфейс для отладки отправки email сообщения                   |
