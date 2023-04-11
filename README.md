# Fruits Application

It's built by Symfony 5.4 PHP framework for backend and VueJS 2.6 for frontend.

## Pre-requistics

Install `PHP(>=7.3.x)`, `Composer`, `Symfony CLI`, `MySQL8.0`, `Node(>=14.x)`

## Installable Guide

Symfony backend code is in `fruits-api` and Vue frontend code is in `fruits-frontend`.

---
### Install & Run Symfony backend

1. Install vendors using `composer`
    ```
    cd fruits-api
    composer install
    ```

2. Configure MySQL database using `DATABASE_URL` in `.env` file.
You can change MySQL username, password, and DB name in the existing environment variable.
    ```
    DATABASE_URL="mysql://root:password@127.0.0.1:3306/fruits?serverVersion=8&charset=utf8mb4"
    ```

3. Create a MySQL database with configured DB name(e.g, fruits) using the following command in Terminal.
    ```
    php bin/console doctrine:database:create
    ```
4. Migrate a database with exising migrations.
    ```
    php bin/console doctrine:migrations:migrate
    ```
    After this command, you can check `fruit` table is created in `fruits` database using MySQL tool.(e.g, Navicat, PHPMyadmin).

5. Change `MAILER_DSN`, `MAILER_FROM_EMAIL` and `MAILER_TO_EMAIL` with a valid email service provider, sender's email address, and a dummy email in `.env` file.
6. Run a console script for getting all fruits from https://fruityvice.com/ and saving them into local DB using the following command from terminal.
    ```
    php bin/console fruits:fetch
    ```
    When all fruits are saved into the DB, it sends an email about it to a dummy email(test@gmail.com)
7. Run the Symfony application.
    ```
    symfony server:start
    ```
    Then it will be run in http://127.0.0.1:8000.
---
### Install & Run VueJS frontend
```
cd fruits-frontend
npm install
npm run serve
```
Then VueJS will be run in http://localhost:8080.

Now you can visit fruits page and add/remove fruits to favorites with fruits data saved in local DB.