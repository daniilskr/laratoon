### Vue 3(+TypeScipt) + Laravel 9
> Laratoon это вертикальный срез (он не является полнофункциональным приложением, лишь минимально реализует самые основные возможности) веб-сайта для чтения онлайн комиксов, который я написал в свободное время, чтобы опробовать возможности `Vue 3` и `TypeScript`.

Иллюстрации взяты из open-source веб-комикса "Papper&Carrot" за авторством Давида Ревуа.

## Как поднять:

#### Требования:
- Windows
- Git
- PHP (от 8.0.2) + Composer
- MySQL (8.0)
#### Откройте MySql 8.0 Command Line Client, пройдите аутентификацию и введите:
```sql
CREATE DATABASE laratoon;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'laravel';
GRANT ALL PRIVILEGES ON laratoon.* TO 'laravel'@'localhost';
```

#### Откройте терминал в папке, куда хотите поместить проект, выделите разом все следующие команды и вставьте их в терминал щелчком по правой кнопке мыши:
```bash
git clone https://github.com/IgorLockyh/laratoon.git
cd laratoon
copy .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
php -r "echo 'Hello world!';"
```

#### Откройте веб-браузер, и введите [http://127.0.0.1:8000/](http://127.0.0.1:8000/) в адресную строку - откроется поднявшийся сайт.

## Скриншоты
Каталог
![catalog page](./public/images/screenshot1.png)
Страница комикса
![comic page](./public/images/screenshot2.png)
Комментарии на странице комикса
![comic page comments](./public/images/screenshot3.png)
