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
docker rmi sail-8.3/app:latest

# Se ejecuta el contenedor de docker
docker-compose up -d

# Instala las dependencias de php
docker-compose exec laravel.test composer install

# Reiniciar el php con los paquetes ya instalados
docker-compose down
docker-compose up -d

# Se ejecuta el comando para crear la base de datos
docker-compose exec laravel.test php artisan migrate
docker-compose exec laravel.test php artisan db:seed
