# favi-example-rest-api

## Docker

It is possible to use Docker for easier work with the database. You can prepare the database using the command:
```docker compose up```

## Steps to start the project

Set the env database value in .env.dev/.env.test according to the environment.

For the project to work properly, you need to run ```composer install```

Then you need to create a schema using the command:
```php bin/console doc:sch:cre```

and now you just need to run the tests ```php bin/phpunit```
