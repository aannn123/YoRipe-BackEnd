# BackEnd Test Review Application

###  Installation

1. Install project

```bash

# $ git clone https://github.com/aannn123/YoRipe-BackEnd.git

```
2. Composer install

```bash

$ cd your_name_directory
$ composer install

```

3. Generate Key

copy .env.example and rename to .env

```bash

$ cp .env.example .env

$ php artisan key:generate

```

4. Setting Database
Go to .env and change this

```bash

DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=your_password

```

5. Run vendor:publish for table personal access token from laravel sanctum

```bash

$ php artisan vendor:publish --tag=sanctum-migrations

```

6. Migrate and Seeding database

```bash

$ php artisan migrate
$ php artisan db:seed --class=DatabaseSeeder

```

7. Run application

```bash

$ php artisan serve

```

8. And run this route


```bash

    POST    /login = for login

    need authentication

    GET     /blog = for list all data by role login
    POST    /blog = for create blog
    PUT     /blog/{id} = for update blog
    DELETE  /blog/{id} = for delete blog
    GET     /blog/{id} = for detail blog by id

    for admin

    POST    /admin/create/user = for create user
    
    Authentication Login
    
    for access route except login
    in headers postman add this
    Accept              Application/json
    Authorization       Bearer {token_login}

```

9. Run Unit Test

```bash

$ php artisan test

note: change token in file BlogTest and AdminTest
```