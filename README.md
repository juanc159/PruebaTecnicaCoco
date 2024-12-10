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
1. **Repository Pattern**: El patrón Repository se utiliza para separar la lógica de acceso a datos de la lógica de negocio. Los repositorios `ResourceRepository` y `ReservationRepository` gestionan las consultas a la base de datos y mejoran la mantenibilidad del código.
2. **Factory Pattern**: Este patrón facilita la creación de objetos de reserva, permitiendo extender la funcionalidad si se añaden más tipos de recursos en el futuro.

### Validación de Conflictos de Reserva
- Al crear una nueva reserva, la API valida si el recurso está disponible en el horario solicitado. Si hay un conflicto (es decir, si ya existe una reserva en ese horario), no se permite crear la nueva reserva.

## Decisiones de Diseño

### Elección de Patrones
- **Repository Pattern**: Elegimos este patrón para separar las responsabilidades de acceso a la base de datos de la lógica de negocio. Esto hace que el código sea más limpio y escalable.
- **Factory Pattern**: Se usó para facilitar la creación de reservas. Este patrón permite agregar diferentes tipos de recursos sin afectar otras partes del sistema.

### Consultas y Validaciones
- La consulta de disponibilidad y la validación de conflictos de horarios se gestionan directamente en los controladores, utilizando la lógica de repositorio para verificar la disponibilidad de los recursos antes de crear una reserva.

## Instrucciones de Configuración

### Requisitos
- PHP 8.1 o superior
- Composer
- Laravel 11.x
- MySQL o cualquier base de datos compatible

### Pasos para Configurar el Proyecto

1. **Clonar el Repositorio**:
   ```bash
   https://github.com/juanc159/PruebaTecnicaCoco.git
   cd pruebaTecnicaCoco

2. **Instalar las Dependencias**:
   ```bash
   composer install

3. **Configurar el archivo**:
   ```bash
   mv .env.example .env

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistema_reservas
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña

4. **Generar la Clave de la Aplicación**:
   ```bash
   php artisan key:generate

5. **Ejecutar las Migraciones**:
   ```bash
   php artisan migrate

6. **Iniciar el Servidor**:
   ```bash
   php artisan serve


## Rutas y Endpoints de la API

1. **GET /resources**:
#### Descripción:
    Lista todos los recursos disponibles.
#### Respuesta:
    ```bash
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

2. **GET /resources/{id}/availability**:
#### Descripción:
    Consulta la disponibilidad de un recurso en un horario específico.
#### Parámetros:
    date_time: Fecha y hora en formato YYYY-MM-DD HH:MM:SS.
#### Respuesta: 
    {
        "available": true
    }

3. **POST /reservations**:
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


4. **DELETE /reservations/{id}**:
#### Descripción:
    Cancela una reserva existente. 
#### Respuesta: 
    {
  "message": "Reserva cancelada con éxito"
}



