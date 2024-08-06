## Primera instalacion
Para instalar en un entorno de desarrollo ejecute el comando

`docker-compose up -d` para levantar los servicios

`docker-compose exec laravel.test php artisan migrate` para crear la estructura de la base de datos

`docker-compose exec laravel.test php artisan db:seed` para crear datos de prueba

`docker-compose exec laravel.test php artisan passport:client` para crear el cliente de passport

```
docker-compose exec laravel.test php artisan migrate
docker-compose exec laravel.test php artisan db:seed
docker-compose exec laravel.test php artisan passport:client
```

Si desea recompilar los servicios en entorno de develop puede usar el comando

`sh start.sh` para eliminar y ejecutar el docker-compose
