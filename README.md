# API RESTful para Gestión de Reservas de Recursos Compartidos

Este proyecto es una API RESTful desarrollada en Laravel para gestionar reservas de recursos compartidos, como salas de reuniones o equipos de oficina. La API permite crear, consultar la disponibilidad y cancelar reservas de estos recursos.

## Estructura y Diseño del Sistema

### Modelos
1. **Resource**: Este modelo representa los recursos disponibles para ser reservados. Cada recurso tiene las siguientes propiedades:
   - `name`: Nombre del recurso (por ejemplo, "Sala de Reuniones A").
   - `description`: Descripción del recurso (por ejemplo, "Sala equipada con proyector y aire acondicionado").
   - `capacity`: La capacidad del recurso (por ejemplo, número máximo de personas que pueden usarlo).
   
2. **Reservation**: Este modelo representa una reserva hecha para un recurso. Tiene las siguientes propiedades:
   - `resource_id`: Relacionado con el recurso que se reserva.
   - `reserved_at`: Fecha y hora de la reserva.
   - `duration`: Duración de la reserva en horas.
   - `status`: Estado de la reserva (por ejemplo, "confirmed", "pending", "cancelled").

### Controladores
1. **ResourceController**: Gestiona las operaciones relacionadas con los recursos. Permite listar los recursos y consultar su disponibilidad.
2. **ReservationController**: Maneja las operaciones de reservas. Permite crear reservas, verificar la disponibilidad y cancelar reservas.

### Patrones de Diseño
1. **Repository Pattern**: El patrón Repository se utiliza para separar lógica de negocio. Los repositorios `ResourceRepository` y `ReservationRepository` gestionan las consultas a la base de datos y mejoran la mantenibilidad del código.

### Validación de Conflictos de Reserva
- Al crear una nueva reserva, la API valida si el recurso está disponible en el horario solicitado. Si hay un conflicto (es decir, si ya existe una reserva en ese horario), no se permite crear la nueva reserva.


### Consultas y Validaciones
- La consulta de disponibilidad y la validación de conflictos de horarios se gestionan directamente en los controladores, utilizando la lógica de repositorio para verificar la disponibilidad de los recursos antes de crear una reserva.

## Instrucciones de Configuración

### Requisitos
- PHP 8.2 o superior
- Composer
- Laravel 11.x
- MySQL o cualquier base de datos compatible

### Pasos para Configurar el Proyecto

1. **Clonar el Repositorio**:
   https://github.com/juanc159/PruebaTecnicaCoco.git
   cd pruebaTecnicaCoco

2. **Instalar las Dependencias**:
   composer install

3. **Configurar el archivo**:
   mv .env.example .env

    DB_CONNECTION=mysql

    DB_HOST=127.0.0.1

    DB_PORT=3306

    DB_DATABASE=sistema_reservas

    DB_USERNAME=tu_usuario
    
    DB_PASSWORD=tu_contraseña

4. **Generar la Clave de la Aplicación**:
   php artisan key:generate

5. **Ejecutar las Migraciones**:
   php artisan migrate

6. **Ejecutar las seeder**: 
    es para tener informacion en la tabla  resources
   
   php artisan db:seed --class=ResourceSeeder

7. **Iniciar el Servidor**:
   php artisan serve


## Rutas y Endpoints de la API

#### para poder usar las apis deberá registrar un usuario y loguearse, se implemento un sistema de authenticación básico  con JWT
#### se deja en la raiz el archivo Prueba Coco.postman_collection.json

1. **GET /api/register**:
#### Descripción:
    Registrar un usuario.
#### Respuesta: 
    {
        "name": "Juan Carlos",
        "email": "prueba@gmail.com", 
        "password": "123456789"
    }

2. **GET /api/login**:
#### Descripción:
    Registrar un usuario.
#### Respuesta: 
    { 
        "email": "prueba@gmail.com", 
        "password": "123456789"
    }



3. **GET /api/resources**:
#### Descripción:
    Lista todos los recursos disponibles.
#### Respuesta:
    [
        {
            "id": 1,
            "name": "Sala de Reuniones A",
            "description": "Sala equipada con proyector y aire acondicionado",
            "capacity": 10
        },
        {
            "id": 2,
            "name": "Equipo de Oficina B",
            "description": "Equipo de computación para uso general",
            "capacity": 5
        }
    ]

4. **GET /api/resources/{id}/availability**:
#### Descripción:
    Consulta la disponibilidad de un recurso en un horario específico.
#### Parámetros:
    date_time: Fecha y hora en formato YYYY-MM-DD HH:MM:SS.
#### Respuesta: 
    {
        "available": true
    }

3. **POST /api/reservations**:
#### Descripción:
    Crea una nueva reserva para un recurso.
#### Cuerpo de la Solicitud: 
    {
        "resource_id": 1,
        "reserved_at": "2024-12-15 10:00:00",
        "duration": 2
    }
#### Respuesta: 
    {
        "id": 1,
        "resource_id": 1,
        "reserved_at": "2024-12-15 10:00:00",
        "duration": 2,
        "status": "confirmed"
    }


4. **DELETE /api/reservations/{id}**:
#### Descripción:
    Cancela una reserva existente. 
#### Respuesta: 
    {
        "message": "Reserva cancelada correctamente"
    }
