#!/bin/bash

echo "START"

# Comprueba si existe el archivo .env
if [ ! -f .env ]; then
  # Si no existe, copia el archivo .env.example a .env
  cp .env.example .env
fi

# Se detiene y elimina el docker
docker-compose down
docker-compose rm

# Se borra una anterior imagen
docker rmi bancos-virtuales-node:latest

# Se ejecuta el contenedor de docker
docker-compose up -d

# Se ejecuta el comando para crear la base de datos
docker-compose exec laravel.test php artisan migrate
docker-compose exec laravel.test php artisan db:seed
