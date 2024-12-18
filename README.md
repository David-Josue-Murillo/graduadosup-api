# graduadosup-api

API RESTful para la gestión de graduados de la Universidad de Panamá

## Tabla de Contenidos

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Migraciones](#migraciones)
- [Endpoints](#endpoints)
- [Ejemplos de Uso](#ejemplos-de-uso)
- [Testing](#testing)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)


## Requisitos

- **PHP** >= 8.0
- **Laravel** >= 9.x
- **Composer** para gestión de dependencias
- **MySQL** o cualquier otra base de datos compatible con Laravel


## Instalación

1. Clonar el repositorio

```bash
git clone https://github.com/david-murillo/graduadosup-api.git
cd graduadosup-api
```

2. Instalar dependencias

```bash
composer install
```

3. Copia el archivo de entorno de ejemplo y configura las variables de entorno

```bash
cp .env.example .env
```


## Configuración

Asegurate de configurar las variables de entorno en el archivo `.env` según tus necesidades.
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```


## Migraciones
Para crear las tablas necesarias, ejecuta el siguiente comando:
```bash
php artisan migrate
```


## Endpoints

Los endpoints disponibles para las `graduates` están organizados bajo el prefijo `api/graduates`:

| Endpoint | Descripción |
| --- | --- |
| `GET /api/graduates` | Lista todas la cantidad de graduados |
| `POST /api/graduates` | Crea una nueva cantidad de graduados |
| `GET /api/graduates/{id}` | Muestra una cantidad de graduados específica |
| `PUT /api/graduates/{id}` | Actualiza una cantidad de graduados específica |
| `DELETE /api/graduados/{id}` | Elimina una cantidad de graduados específica |
| `GET /api/graduates/{id}/campus` | Muestra el centro regional a que pertenece una cantidad de graduados específica |
| `GET /api/graduates/{id}/campus` | Muestra la facultad la carrera a quepertenece una cantidad de graduados específica |
| `GET /api/graduates/{id}/faculty` | Muestra la facultad a que pertenece una cantidad de graduados específica |
| `GET /api/graduates?year=2024` | Filtrar por año |
| `GET /api/graduates?campus_id=1` | Filtrar por centro regional |
| `GET /api/graduates?career_id=1` | Filtrar por carrera |


Los endpoints disponibles para las `faculties` están organizados bajo el prefijo `api/faculties`:

| Endpoint | Descripción |
| --- | --- |
| `GET /api/faculties` | Lista todas las facultades |
| `POST /api/faculties` | Crea una nueva facultad |
| `GET /api/faculties/{id}` | Muestra una facultad específica |
| `PUT /api/faculties/{id}` | Actualiza una facultad específica |
| `DELETE /api/faculties/{id}` | Elimina una facultad específica |
| `GET /api/faculties/{id}/careers` | Muestra las carreras de una facultad específica |


Los endpoints disponibles para las `careers` están organizados bajo el prefijo `api/careers`:

| Endpoint | Descripción |
| --- | --- |
| `GET /api/careers` | Lista todas las carreras |
| `POST /api/careers` | Crea una nueva carrera |
| `GET /api/careers/{id}` | Muestra una carrera específica |
| `PUT /api/careers/{id}` | Actualiza una carrera específica |
| `DELETE /api/careers/{id}` | Elimina una carrera específica |
| `GET /api/careers/{id}/faculty` | Muestra la facultad a que pertenece |


## Ejemplos de Uso

### Facultades
```bash
# Lista todas las facultades
curl -X GET http://localhost:8000/api/graduates
```

```json
[
    {
        "id": 1,
        "quantity": 38,
        "year": 2024,
        "campus": {
            "id": 2,
            "name": "Centro Regional Universitario de Penonome"
        },
        "career": {
            "id": 16,
            "name": "Lic. Informática para la Gestión Educativa y Empresarial",
            "faculty": {
                "id": 5,
                "name": "Facultad de Informática, Electrónica y Comunicación"
            }
        }
    },
    {
        "id": 2,
        "quantity": 56,
        "year": 2023,
        "campus": {
            "id": 2,
            "name": "Centro Regional Universitario de Veraguas"
        },
        "career": {
            "id": 16,
            "name": "Lic. Banca y Finanzas",
            "faculty": {
                "id": 5,
                "name": "Facultad de Economía y Negocios"
            }
        }
    } 
]
```

### Carreras
```bash
# Lista todas las carreras
curl -X GET http://localhost:8000/api/careers
```

```json
[
    {
        "id": 1,
        "name": "Ingeniería",
        "description": "Ingeniería",
        "created_at": "2022-03-30T15:00:00.000000Z",
        "updated_at": "2022-03-30T15:00:00.000000Z"
    },
    {
        "id": 2,
        "name": "Ciencias",
        "description": "Ciencias",
        "created_at": "2022-03-30T15:00:00.000000Z",
        "updated_at": "2022-03-30T15:00:00.000000Z"
    }
]
```


## Testing

Para ejecutar los tests, ejecuta el siguiente comando:
```bash
php artisan test
```


## Contribuciones

Si deseas contribuir a este proyecto, puedes hacerlo de varias maneras:

- Reportando errores
- Haciendo solicitudes de funcionalidades
- Haciendo Pull Requests
- Haciendo Pull Requests con código
- Haciendo Pull Requests con documentación
- Haciendo Pull Requests con tests
- Haciendo Pull Requests con traducciones
- Haciendo Pull Requests con otras contribuciones

Puedes ver todos los PRs abiertos en el repositorio [aquí](https://github.com/david-murillo/graduadosup-api/pulls).


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> c54b76f (Set up a fresh Laravel app)
