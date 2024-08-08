## Instalacion

### Automatica
Para instalar en un entorno de desarrollo puede ejecutar el string start

`sh start.sh`

### Manual
Copiar el archivo .env.example -> .env

ejecutar `docker-compose up -d` para levantar los servicios

`docker-compose exec laravel.test composer install` para instalar las dependencias de php

`docker-compose exec laravel.test php artisan migrate` para crear la estructura de la base de datos

`docker-compose exec laravel.test php artisan db:seed` para crear datos de prueba

Si es la primera ejecucion: Reiniciar el contenedor php para ejecutar el servidor con los paquetes instalados
`docker-compose down && docker-compose up -d`

## Diagrama de casos de uso
```
actor Usuario
actor "API de Giphy" as Giphy

Usuario --> (Logearse)
Usuario --> (Consultar Paginado de GIFs)
Usuario --> (Pedir GIF por ID)
Usuario --> (Agregar GIF a Favoritos)
Usuario --> (Pedir Lista de GIFs Favoritos)

(Consultar Paginado de GIFs) --> Giphy : Obtener GIFs
(Pedir GIF por ID) --> Giphy : Obtener GIF por ID
(Pedir Lista de GIFs Favoritos) --> Giphy : Obtener GIFs
(Agregar GIF a Favoritos) --> "Base de Datos" : Guardar Favoritos
(Pedir Lista de GIFs Favoritos) --> "Base de Datos" : Obtener IDs de Favoritos
```

## Diagrama de secuencias

Logearse
```
participant Usuario
participant Sistema

Usuario->>Sistema: Ingresar credenciales
Sistema->>Sistema: Verificar credenciales
Sistema->>Usuario: Retornar token
```

Consultar Paginado de GIFs
```
participant Usuario
participant Sistema
participant Giphy

Usuario->>Sistema: Consultar paginado de GIFs
Sistema->>Giphy: Solicitar GIFs paginados
Giphy->>Sistema: Devolver GIFs
Sistema->>Sistema: Guardar GIFs en caché
Sistema->>Usuario: Devolver GIFs paginados
```

Pedir GIF por ID
```
participant Usuario
participant Sistema
participant Giphy

Usuario->>Sistema: Pedir GIF por ID
Sistema->>Giphy: Solicitar GIF por ID
Giphy->>Sistema: Devolver GIF
Sistema->>Sistema: Guardar GIF en caché
Sistema->>Usuario: Devolver GIF
```

Agregar GIF a Favoritos
```
participant Usuario
participant Sistema
participant BaseDeDatos

Usuario->>Sistema: Agregar GIF a favoritos
Sistema->>BaseDeDatos: Guardar ID de usuario y GIF
BaseDeDatos->>Sistema: Confirmar guardado
Sistema->>Usuario: Confirmar adición a favoritos
```

Pedir Lista de GIFs Favoritos
```
participant Usuario
participant Sistema
participant BaseDeDatos
participant Giphy

Usuario->>Sistema: Pedir lista de GIFs favoritos
Sistema->>BaseDeDatos: Obtener IDs de GIFs favoritos
BaseDeDatos->>Sistema: Devolver IDs de GIFs favoritos
Sistema->>Giphy: Solicitar GIFs por IDs
Giphy->>Sistema: Devolver GIFs
Sistema->>Usuario: Devolver lista de GIFs favoritos
```