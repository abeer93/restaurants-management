# restaurants Management
An small app that has one api to create an order and notify operation by sending email when stock become less than 50%.

## Requirement

1. [Laravel 9.x](https://laravel.com/docs/9.x)
2. [PHP >= 8.1](http://php.net/downloads.php)
3. [Composer](https://getcomposer.org/)

## Installation
1. Clone the repo via this url
  ```
    git clone https://github.com/abeer93/restaurants-management.git
  ```

2. Enter inside the folder
```
  cd restaurants-management
```
3. Create a `.env` file by running the following command
  ```
    cp .env.example .env
  ```
  ```
4. Install various packages and dependencies:
  ```
    composer install
  ```
5. Generate an encryption key for the app:
  ```
    app php artisan key:generate
  ```
6. Run migartions
  ```
    app php artisan migrate --seed
  ```
7. Run test cases
    before running next commands please make sure fill all databse test variables which begin with DB_TEST_ in .env file.

  ```
    php artisan optimize:clear
    vendor/bin/phpunit
  ```
8. Run Servers
  ```
    php artisan serve --port 8080
  ```

### Test The APP
Now, open your web browser and go to `http://localhost:8080/api/documentation` and check the swagger documentation and try it out.


## Important Environment variables (dev)

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `APP_ENV` | `string` | `local` | Environment |
| `APP_DEBUG` |`boolean`| `true` | Debug mode |
| `APP_KEY` | `string` | `SomeRandomStringWith32Characters` | Application key |
| `DB_CONNECTION` | `string` | `mysql` | DB connection to use |
| `DB_HOST` | `string` | `mysql` | Hostname to connect |
| `DB_DATABASE` | `string` | `laravel` | Database name |
| `DB_USERNAME` | `string` | `root` | Database username |
| `DB_PASSWORD` | `string` | `empty` | Database password |
| `DB_TEST_DRIVER` | `string` | `mysql` | DB connection to use in running test cases |
| `DB_TEST_HOST` | `string` | `mysql` | Hostname to connect to run test cases |
| `DB_TEST_DATABASE` | `string` | `laravel_test` | Database name to run test cases|
| `DB_TEST_USERNAME` | `string` | `root` | Database username for databas to run test cases|
| `DB_TEST_PASSWORD` | `string` | `empty` | Database password for database to run test cases|
| `MAIL_HOST` | `string` | `empty` | mail host|
| `MAIL_PORT` | `integer` | `empty` | mail post|
| `MAIL_USERNAME` | `string` | `empty` | mail host user name|
| `MAIL_PASSWORD` | `string` | `empty` | mail host password|


### Images

![Database Diagram](https://github.com/abeer93/restaurants-management/blob/master/sql-model.png)
![Low Stock Email](https://github.com/abeer93/restaurants-management/blob/master/low-stock-email.png)

### Docs & Help

- [Laravel 9.x Documentation](https://laravel.com/docs/9.x)
- [DarkOnlineSwagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)