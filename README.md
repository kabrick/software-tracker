# Software Tracker PHP

![ScreenShot](/public/assets/img/screenshots/screenshot_1.png)

![ScreenShot](/public/assets/img/screenshots/screenshot_2.png)

![ScreenShot](/public/assets/img/screenshots/screenshot_3.png)

-   Create a database locally named revenue_collection
-   Run `curl -sS https://getcomposer.org/installer | php` to install [composer](https://getcomposer.org/download/).
-   Run `git clone https://github.com/kabrick/revenue_collection_php.git` to pull this project.
-   Rename `.env.example` file to `.env` inside the project root and fill in the database information. You could simply `run mv .env.example .env`. For example using MySQL:

    ```txt
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=revenue_collection
    DB_USERNAME=root
    DB_PASSWORD=<secret>
    ```

-   In the project root directory, run:

    ```txt
    - composer install
    - composer update
    - npm i
    - php artisan key:generate
    - php artisan migrate --seed
    - php artisan serve
    ```

-   Other useful commands for debugging when in trouble:

    ```txt
    - composer dump-autoload
    - php artisan config:clear
    - php artisan cache:clear
    - php artisan clear-compiled
    - php artisan view:clear
    ```
