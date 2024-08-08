## Instalación

### Automática
Para instalar en un entorno de desarrollo puede ejecutar el string start

`sh start.sh`

### Manual
Copiar el archivo .env.example -> .env

ejecutar `docker-compose up -d` para levantar los servicios

`docker-compose exec laravel.test composer install` para instalar las dependencias de php

`docker-compose exec laravel.test php artisan migrate` para crear la estructura de la base de datos

`docker-compose exec laravel.test php artisan db:seed` para crear datos de prueba

Si es la primera ejecución: Reiniciar el contenedor php para ejecutar el servidor con los paquetes instalados
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

## Diagrama de datos
```
Users {
    bigint unsigned id
    varchar name
    varchar email
    timestamp email_verified_at
    varchar password
    varchar remember_token
    timestamp created_at
    timestamp updated_at
}

Favorites {
    bigint unsigned id
    bigint unsigned user_id
    varchar ghipy_id
    varchar alias
    timestamp created_at
    timestamp updated_at
}

Sessions {
    varchar id
    bigint unsigned user_id
    varchar ip_address
    text user_agent
    longtext payload
    int last_activity
}

OauthAccessTokens {
    varchar id
    bigint unsigned user_id
    char client_id
    varchar name
    text scopes
    tinyint revoked
    timestamp created_at
    timestamp updated_at
    datetime expires_at
}

OauthClients {
    char id
    bigint unsigned user_id
    varchar name
    varchar secret
    varchar provider
    text redirect
    tinyint personal_access_client
    tinyint password_client
    tinyint revoked
    timestamp created_at
    timestamp updated_at
}

OauthRefreshTokens {
    varchar id
    varchar access_token_id
    tinyint revoked
    datetime expires_at
}

PasswordResetTokens {
    varchar email
    varchar token
    timestamp created_at
}

Jobs {
    bigint unsigned id
    varchar queue
    longtext payload
    tinyint unsigned attempts
    int unsigned reserved_at
    int unsigned available_at
    int unsigned created_at
}

FailedJobs {
    bigint unsigned id
    varchar uuid
    text connection
    text queue
    longtext payload
    longtext exception
    timestamp failed_at
}

Cache {
    varchar key
    mediumtext value
    int expiration
}

CacheLocks {
    varchar key
    varchar owner
    int expiration
}

JobBatches {
    varchar id
    varchar name
    int total_jobs
    int pending_jobs
    int failed_jobs
    longtext failed_job_ids
    mediumtext options
    int cancelled_at
    int created_at
    int finished_at
}

Migrations {
    int unsigned id
    varchar migration
    int batch
}

OauthAuthCodes {
    varchar id
    bigint unsigned user_id
    char client_id
    text scopes
    tinyint revoked
    datetime expires_at
}

OauthPersonalAccessClients {
    bigint unsigned id
    char client_id
    timestamp created_at
    timestamp updated_at
}

Users ||--o{ Favorites : "tiene"
Users ||--o{ Sessions : "tiene"
Users ||--o{ OauthAccessTokens : "tiene"
Users ||--o{ OauthClients : "tiene"
OauthAccessTokens ||--o{ OauthClients : "pertenece a"
OauthRefreshTokens ||--o{ OauthAccessTokens : "pertenece a"
```

## Postman collection
Importar la colección `postman_collection.json`
