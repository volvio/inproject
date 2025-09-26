Для просмотра доступна 

страница мероприятий
http://localhost/events

страница  регистрации
http://localhost/events/register

для разворачивания проекта ипользуется docker
образы настроены на laravel 10 php 8.2

Для разворачивания проекта выполнить команды
1.Установить зависимости Composer
composer install

2. Запустить контейнеры Docker через Sail
./vendor/bin/sail up -d

3/Сгенерировать ключ приложения
./vendor/bin/sail artisan key:generate

4. Запустить миграции и сидеры
./vendor/bin/sail artisan migrate --seed

5. Собрать фронтенд (CSS/JS)
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

6. (Опционально) Запустить тесты
./vendor/bin/sail artisan test