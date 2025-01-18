Paso 1: Arrancar Docker y la base de datos
1.-Inicia el contenedor de MariaDB: Abre una terminal y ejecuta:
docker start mariadb-server

Accede a MariaDB desde el contenedor: Una vez iniciado, ejecuta
docker exec -it mariadb-server mariadb -u root -p

Luego ingresa la contraseña que tienes configurada:
m1_s3cr3t

Crea la base de datos para el proyecto: Dentro del cliente de MariaDB, ejecuta:
CREATE DATABASE igformacion;
======================================
Paso 2: Configurar Laravel

    Crear un nuevo proyecto de Laravel: En tu terminal, ve al directorio donde trabajarás y ejecuta:

composer create-project laravel/laravel PracticaFinal_IGFormacion

Esto descargará e instalará Laravel en una carpeta llamada PracticaFinal_IGFormacion.


-Accede al proyecto:

cd PracticaFinal_IGFormacion

-Configurar el archivo .env: Edita el archivo .env en el proyecto con VSCode para conectar Laravel a tu base de datos. Cambia las siguientes líneas:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igformacion
DB_USERNAME=root
DB_PASSWORD=m1_s3cr3t

-Configuración para SESSION_DRIVER=database
o =file
Paso 1: Configurar el archivo .env
    SESSION_DRIVER=database

Esto le dice a Laravel que almacene las sesiones en una tabla de la base de datos o en un file

Configura el archivo .env con los datos de conexión a tu base de datos.
Ejecuta:

php artisan migrate
--------------------

    Abre el archivo .env en la raíz del proyecto.

Paso 3: Arrancar el servidor local de Laravel

    -Levantar el servidor: Una vez configurado, inicia el servidor de desarrollo de Laravel:

php artisan serve

Esto abrirá un servidor en http://127.0.0.1:8000.


--------------------------
Comando para generar un modelo con migración

Por cada entidad, ejecuta:

php artisan make:model NombreModelo -m

Por ejemplo:

php artisan make:model Estudiante -m
php artisan make:model Curso -m
php artisan make:model Profesor -m
php artisan make:model Matricula -m
php artisan make:model Asignatura -m
php artisan make:model Evaluacion -m

Esto creará:

    El modelo correspondiente en app/Models.
    Un archivo de migración en database/migrations.

    ((si quisiera hacerlo sin -m tendria que hacer lo siguiente:))

    aso 1: Crear solo el modelo

Usa el comando sin el flag -m para generar solo el modelo:

php artisan make:model Curso

Esto generará únicamente el modelo en la carpeta app/Models/, sin crear la migración asociada.
Paso 2: Crear la migración por separado

Luego, crea la migración para la tabla correspondiente con este comando:

php artisan make:migration create_cursos_table

---------------------------
2. Editar las migraciones

Edita cada archivo de migración en database/migrations para definir las columnas y relaciones según tu diagrama.

Por ejemplo, para la tabla estudiantes:

public function up()
{
    Schema::create('estudiantes', function (Blueprint $table) {
        $table->id(); // ID principal
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('email')->unique();
        $table->string('telefono')->nullable();
        $table->date('fecha_nacimiento');
        $table->timestamps(); // created_at, updated_at
    });
}

public function up()
{
    Schema::create('cursos', function (Blueprint $table) {
        $table->id(); // ID principal
        $table->string('nombre');
        $table->text('descripcion');
        $table->integer('duracion'); // En semanas o meses
        $table->date('fecha_inicio');
        $table->date('fecha_fin');
        $table->timestamps(); // created_at, updated_at
    });
}

public function up()
{
    Schema::create('profesores', function (Blueprint $table) {
        $table->id(); // ID principal
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('email')->unique();
        $table->string('telefono')->nullable();
        $table->string('especialidad');
        $table->timestamps(); // created_at, updated_at
    });
}

public function up()
{
    Schema::create('asignaturas', function (Blueprint $table) {
        $table->id(); // ID principal
        $table->string('nombre');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
        $table->foreignId('profesor_id')->constrained('profesores')->onDelete('cascade');
        $table->timestamps(); // created_at, updated_at
    });
}

public function up()
{
    Schema::create('matriculas', function (Blueprint $table) {
        $table->id(); // ID principal
        $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
        $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
        $table->date('fecha_matricula');
        $table->timestamps(); // created_at, updated_at
    });
}

public function up()
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id(); // ID principal
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('asignatura_id')->constrained('asignaturas')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->decimal('nota', 5, 2); // Ejemplo: nota con hasta 2 decimales
            $table->timestamps(); // created_at, updated_at
        });
    }




7. Ejecución de las migraciones

Después de definir estas migraciones, ejecútalas con el comando:

php artisan migrate

Esto creará las tablas en tu base de datos según las relaciones y campos definidos.


--------


. Configurar los modelos

Después de crear los modelos, necesitas configurarlos para que reflejen las relaciones entre las tablas. Veamos cómo sería el modelo Curso como ejemplo:(((Apuntarse las difencias entre hasmany etc etc, estan en las diapositivas)))
Modelo Curso

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos'; // Nombre de la tabla

    // Relación: Un curso tiene muchas asignaturas
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'curso_id');
    }
}


Modelo Estudiante




namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    // Relación: Un estudiante puede tener muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    // Relación: Un estudiante puede tener muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'estudiante_id');
    }
}

Modelo Asignatura

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    // Relación: Una asignatura pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: Una asignatura tiene muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'asignatura_id');
    }

    // Relación: Una asignatura tiene un profesor
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }
}

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas'; // Nombre de la tabla

    // Relación: Una matrícula pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Una matrícula pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones'; // Nombre de la tabla

    // Relación: Una evaluación pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Una evaluación pertenece a una asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    // Relación: Una evaluación pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores'; // Nombre de la tabla

    // Relación: Un profesor imparte muchas asignaturas
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'profesor_id');
    }
}


----------------------------------










