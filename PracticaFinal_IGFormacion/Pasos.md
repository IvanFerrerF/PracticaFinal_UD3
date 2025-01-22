## Arrancar Docker y la base de datos
1.-Inicia el contenedor de MariaDB: Abre una terminal y ejecuta:
```bash
docker start mariadb-server
```

Accede a MariaDB desde el contenedor: Una vez iniciado, ejecuta
```bash
docker exec -it mariadb-server mariadb -u root -p
```

Luego ingresa la contraseña que tienes configurada:
```bash
m1_s3cr3t
```

Crea la base de datos para el proyecto: Dentro del cliente de MariaDB, ejecuta:
```bash
CREATE DATABASE igformacion_v2;
```

## Configurar Laravel

Crear un nuevo proyecto de Laravel: En tu terminal, ve al directorio donde trabajarás y ejecuta:

```bash
composer create-project laravel/laravel .
```
El punto (.) al final asegura que Laravel se instale en la carpeta actua




## Configurar el archivo .env para la conexión a la base de datos.

Configurar el archivo .env: Edita el archivo .env en el proyecto con VSCode para conectar Laravel a tu base de datos. Cambia las siguientes líneas:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igformacion_v2
DB_USERNAME=root
DB_PASSWORD=m1_s3cr3t
```

-Configuración para 
SESSION_DRIVER=database
o 
SESSION DRIVER=file

Esto le dice a Laravel que almacene las sesiones en una tabla de la base de datos o en un file

```


## Arrancar el servidor local de Laravel

Levantar el servidor: Una vez configurado, inicia el servidor de desarrollo de Laravel:
```bash
php artisan serve
```
Esto abrirá un servidor en http://127.0.0.1:8000.


--------------------------
Comando para generar un modelo con migración

Por cada entidad, ejecuta:

php artisan make:model NombreModelo -m

Por ejemplo:

php artisan make:model Estudiante -m
php artisan make:model Temporada -m
php artisan make:model Curso -m
php artisan make:model Profesor -m
php artisan make:model Matricula -m
php artisan make:model Asignatura -m
php artisan make:model Evaluacion -m
php artisan make:model CursoEstudiante -m



Esto creará:

    El modelo correspondiente en app/Models.
    Un archivo de migración en database/migrations.

    ((si quisiera hacerlo sin -m tendria que hacer lo siguiente:))

    paso 1: Crear solo el modelo

Usa el comando sin el flag -m para generar solo el modelo:

php artisan make:model Estudiante
php artisan make:model Temporada
php artisan make:model Curso
php artisan make:model Profesor
php artisan make:model Matricula
php artisan make:model Asignatura
php artisan make:model Evaluacion
php artisan make:model CursoEstudiante



Esto generará únicamente el modelo en la carpeta app/Models/, sin crear la migración asociada.
Paso 2: Crear la migración por separado

Luego, crea la migración para la tabla correspondiente con este comando:

php artisan make:migration create_estudiantes_table
php artisan make:migration create_cursos_table
php artisan make:migration create_profesores_table
php artisan make:migration create_matriculas_table
php artisan make:migration create_asignaturas_table
php artisan make:migration create_evaluaciones_table
php artisan make:migration create_curso_estudiante_table
php artisan make:migration create_temporadas_table


---------------------------
2. Editar las migraciones

Edita cada archivo de migración en database/migrations para definir las columnas y relaciones según tu diagrama.

Para la tabla estudiantes:

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

Para la tabla cursos:

public function up()
{
    Schema::create('cursos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('descripcion');
        $table->integer('duracion');
        $table->foreignId('id_temporada')->nullable()->constrained('temporadas')->onDelete('set null');
        $table->timestamps();
    });
}

Para la tabla profesores:

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

Para la tabla asignaturas:

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

Para la tabla matrículas:

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

Para la tabla evaluaciones:

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

Para la tabla curso_estudiante:

public function up()
    {
        Schema::create('curso_estudiante', function (Blueprint $table) {
            $table->id(); // Clave primaria de la tabla pivote
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->timestamps();
        });
    }

Para la tabla temporadas:

public function up(): void
    {
        Schema::create('temporadas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamps();
        });
    }


7. Ejecución de las migraciones

Después de definir estas migraciones, ejecútalas con el comando:

php artisan migrate

Esto creará las tablas en tu base de datos según las relaciones y campos definidos.


--------


Modelos, recordar poner el campo $fillable

Para asegurarte de que todos tus modelos funcionen correctamente con los métodos POST y PUT, debes incluir la propiedad $fillable en cada uno de ellos. Aquí los modelos actualizados con los campos asignables masivamente:


Modelo Curso:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion',
        'id_temporada', // Ahora incluye la relación con la tabla Temporada
    ];

    // Relación: Un curso pertenece a una temporada (1:1)
    public function temporada()
    {
        return $this->belongsTo(Temporada::class, 'id_temporada');
    }

    // Relación: Un curso tiene muchas asignaturas (1:N)
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas matrículas (1:N)
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas evaluaciones (1:N)
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'curso_id');
    }

    // Relación: Un curso tiene muchos estudiantes a través de la tabla pivote curso_estudiante (N:M)
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'curso_estudiante', 'curso_id', 'estudiante_id');
    }
}


Modelo Estudiante:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'fecha_nacimiento',
    ];

    // Relación: Un estudiante puede tener muchas matrículas (1:N)
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    // Relación: Un estudiante puede tener muchas evaluaciones (1:N)
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'estudiante_id');
    }

    // Relación: Un estudiante puede estar inscrito en muchos cursos a través de la tabla pivote curso_estudiante (N:M)
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_estudiante', 'estudiante_id', 'curso_id');
    }
}

Modelo Temporada:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporada extends Model
{
    use HasFactory;

    protected $table = 'temporadas'; // Nombre de la tabla asociada al modelo

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
    ];

    // Relación: Una temporada tiene un curso asignado (1:1)
    public function curso()
    {
        return $this->hasOne(Curso::class, 'id_temporada');
    }
}

Modelo Asignatura:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'curso_id',
        'profesor_id',
    ];

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

Modelo Matricula:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'curso_id',
        'estudiante_id',
        'fecha_matricula',
    ];

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

Modelo Evaluacion:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'estudiante_id',
        'asignatura_id',
        'curso_id',
        'nota',
    ];

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

Modelo Profesor:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'especialidad',
    ];

    // Relación: Un profesor imparte muchas asignaturas
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'profesor_id');
    }
}


Se puede dejar el modelo CursoEstudiante tal cual como está en el ejemplo, pero solo si tienes una necesidad específica de interactuar directamente con la tabla pivote.

En este caso, si la tabla curso_estudiante no tiene columnas adicionales más allá de las claves foráneas (curso_id y estudiante_id), puedes optar por NO crear un modelo explícito y manejar todo a través de relaciones belongsToMany en los modelos Curso y Estudiante.

Laravel gestiona automáticamente las tablas pivote para relaciones N:M mediante belongsToMany.

Modelo Curso:

public function estudiantes()
{
    return $this->belongsToMany(Estudiante::class, 'curso_estudiante', 'curso_id', 'estudiante_id');
}

Modelo Estudiante:

public function cursos()
{
    return $this->belongsToMany(Curso::class, 'curso_estudiante', 'estudiante_id', 'curso_id');
}
---------------------------------------
Relaciones en Eloquent

Eloquent es el ORM de Laravel y permite definir relaciones entre modelos de una forma muy sencilla. Aquí te dejo las relaciones más comunes, su significado y cómo usarlas:

1. hasOne

    Significado: Un modelo "tiene uno" relacionado. Se usa para relaciones 1:1 donde un modelo está vinculado con otro como "padre".
    Ejemplo: Una Temporada tiene un Curso.

// Modelo Temporada
public function curso()
{
    return $this->hasOne(Curso::class, 'id_temporada');
}

2. belongsTo

    Significado: Un modelo "pertenece a" otro. Es el lado inverso de hasOne o hasMany. Se usa para indicar que este modelo depende de un modelo "padre".
    Ejemplo: Un Curso pertenece a una Temporada.

// Modelo Curso
public function temporada()
{
    return $this->belongsTo(Temporada::class, 'id_temporada');
}

3. hasMany

    Significado: Un modelo "tiene muchos" relacionados. Se usa para relaciones 1:N donde un modelo está vinculado a varios registros de otro modelo.
    Ejemplo: Un Curso tiene muchas Asignaturas.

// Modelo Curso
public function asignaturas()
{
    return $this->hasMany(Asignatura::class, 'curso_id');
}

4. belongsToMany

    Significado: Un modelo "pertenece a muchos" relacionados. Se usa para relaciones N:M donde los modelos están conectados a través de una tabla pivote.
    Ejemplo: Un Estudiante pertenece a muchos Cursos a través de una tabla curso_estudiante.

// Modelo Estudiante
public function cursos()
{
    return $this->belongsToMany(Curso::class, 'curso_estudiante', 'id_estudiante', 'id_curso');
}

// Modelo Curso
public function estudiantes()
{
    return $this->belongsToMany(Estudiante::class, 'curso_estudiante', 'id_curso', 'id_estudiante');
}

5. hasManyThrough

    Significado: Se usa para acceder a un modelo a través de otro modelo relacionado. Es útil para relaciones 1:N a través de un intermediario.
    Ejemplo: Un Profesor puede tener muchas Evaluaciones a través de Asignaturas.

// Modelo Profesor
public function evaluaciones()
{
    return $this->hasManyThrough(Evaluacion::class, Asignatura::class, 'profesor_id', 'asignatura_id');
}
----------------------------------
REVISA SI ESTO VA AQUI

Ejecutar el comando:
```bash
php artisan install:api
```
y escogemos yes

Esto debería haber generado el archivo api.php en el directorio routes y configurado el RouteServiceProvider correctamente para las rutas de la API.
----------------------------------

8. Creación de Seeders para Poblar Datos de Prueba

Ahora vamos a generar datos de prueba para cada tabla usando los seeders de Laravel.
1. Generar los seeders para cada modelo

Nota sobre la tabla pivote

    Es crucial que ejecutes los seeders de las tablas principales (como cursos y estudiantes) antes de ejecutar el seeder de la tabla pivote (curso_estudiante), ya que esta última depende de las claves foráneas de las tablas principales.

Ejecuta los siguientes comandos en la terminal para crear un seeder por cada tabla:

php artisan make:seeder EstudianteSeeder
php artisan make:seeder ProfesorSeeder
php artisan make:seeder TemporadaSeeder
php artisan make:seeder CursoSeeder
php artisan make:seeder AsignaturaSeeder
php artisan make:seeder MatriculaSeeder
php artisan make:seeder EvaluacionSeeder
php artisan make:seeder CursoEstudianteSeeder


Esto generará los archivos en la carpeta database/seeders.

Configurar cada seeder. Aunque hay varios modos la manera más rapida y que he utilizado es el método DB::table()->insert() para realizar las inserciones masivas con Faker:

1. Seeder de Estudiantes

Archivo: EstudianteSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EstudianteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $estudiantes = array_map(function () use ($faker) {
            return [
                'nombre' => $faker->firstName(),
                'apellidos' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'telefono' => $faker->phoneNumber(),
                'fecha_nacimiento' => $faker->date('Y-m-d', '-18 years'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 10)); // Generar 10 estudiantes

        DB::table('estudiantes')->insert($estudiantes);
    }
}

2. Seeder de Profesores

Archivo: ProfesorSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProfesorSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $profesores = array_map(function () use ($faker) {
            return [
                'nombre' => $faker->firstName(),
                'apellidos' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'telefono' => $faker->phoneNumber(),
                'especialidad' => $faker->randomElement(['Matemáticas', 'Física', 'Informática', 'Historia']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 5)); // Generar 5 profesores

        DB::table('profesores')->insert($profesores);
    }
}

3. Seeder de Cursos

Archivo: CursoSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CursoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $cursos = array_map(function () use ($faker) {
            return [
                'nombre' => $faker->sentence(3),
                'descripcion' => $faker->paragraph(),
                'duracion' => $faker->numberBetween(1, 52),
                'id_temporada' => $faker->numberBetween(1, 3), // Supongamos que tienes 3 temporadas
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 3)); // Generar 3 cursos

        DB::table('cursos')->insert($cursos);
    }
}

4. Seeder de Asignaturas

Archivo: AsignaturaSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $asignaturas = array_map(function () use ($faker) {
            return [
                'nombre' => $faker->words(2, true),
                'curso_id' => $faker->numberBetween(1, 3), // Supongamos que hay 3 cursos
                'profesor_id' => $faker->numberBetween(1, 5), // Supongamos que hay 5 profesores
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 15)); // Generar 15 asignaturas

        DB::table('asignaturas')->insert($asignaturas);
    }
}

5. Seeder de Matrículas

Archivo: MatriculaSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MatriculaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $matriculas = array_map(function () use ($faker) {
            return [
                'estudiante_id' => $faker->numberBetween(1, 10), // Supongamos 10 estudiantes
                'curso_id' => $faker->numberBetween(1, 3), // Supongamos 3 cursos
                'fecha_matricula' => $faker->date('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 20)); // Generar 20 matrículas

        DB::table('matriculas')->insert($matriculas);
    }
}

6. Seeder de Evaluaciones

Archivo: EvaluacionSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EvaluacionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $evaluaciones = array_map(function () use ($faker) {
            return [
                'estudiante_id' => $faker->numberBetween(1, 10),
                'asignatura_id' => $faker->numberBetween(1, 15),
                'curso_id' => $faker->numberBetween(1, 3),
                'nota' => $faker->randomFloat(2, 0, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 50)); // Generar 50 evaluaciones

        DB::table('evaluaciones')->insert($evaluaciones);
    }
}

7. Seeder de Temporadas

Archivo: TemporadaSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemporadaSeeder extends Seeder
{
    public function run()
    {
        DB::table('temporadas')->insert([
            ['fecha_inicio' => '2025-01-01', 'fecha_fin' => '2025-06-30', 'created_at' => now(), 'updated_at' => now()],
            ['fecha_inicio' => '2025-07-01', 'fecha_fin' => '2025-12-31', 'created_at' => now(), 'updated_at' => now()],
            ['fecha_inicio' => '2026-01-01', 'fecha_fin' => '2026-06-30', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

8. Seeder de la tabla pivote CursoEstudiante

Archivo: CursoEstudianteSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CursoEstudianteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $cursoEstudiantes = array_map(function () use ($faker) {
            return [
                'curso_id' => $faker->numberBetween(1, 3), // Supongamos 3 cursos
                'estudiante_id' => $faker->numberBetween(1, 10), // Supongamos 10 estudiantes
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, range(1, 20)); // Generar 20 registros en la tabla pivote

        DB::table('curso_estudiante')->insert($cursoEstudiantes);
    }
}

Registrar y ejecutar los seeders

    Registrar en DatabaseSeeder.php: Asegúrate de añadir todos los seeders al método run():

$this->call([
    EstudianteSeeder::class,
    ProfesorSeeder::class,
    TemporadaSeeder::class,
    CursoSeeder::class,
    AsignaturaSeeder::class,
    MatriculaSeeder::class,
    EvaluacionSeeder::class,
    CursoEstudianteSeeder::class,
]);

Ejecutar los seeders:

    php artisan db:seed

Con estos seeders, todos los datos se generarán automáticamente en las tablas correspondientes utilizando Faker y el método DB::table()->insert()

Hay otras formas de poder hacerlos, con el bucle for y faker por ejemplo:
Seeder de Estudiantes

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;
use Faker\Factory as Faker;

class EstudianteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Estudiante::create([
                'nombre' => $faker->firstName(),
                'apellidos' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'telefono' => $faker->phoneNumber(),
                'fecha_nacimiento' => $faker->date('Y-m-d', '-18 years'),
            ]);
        }
    }
}





Otra forma sería hacerlo mediante factory, Aquí tienes ejemplos:

Seeder de Estudiantes

Archivo: EstudianteSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{
    public function run()
    {
        Estudiante::factory(10)->create(); // Crea 10 estudiantes con la fábrica
    }
}



En el caso de factory despues deberiamos generar las fábricas para los modelos:

php artisan make:factory EstudianteFactory --model=Estudiante

Esto generaría archivos en la carpeta database/factories.

Después tendríamos que configurar las fábricas, en la carpeta database/factories para definir cómo se generan los datos. Aquí tienes ejemplos:

EstudianteFactory

Archivo: EstudianteFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EstudianteFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '-18 years'),
        ];
    }
}


ProfesorFactory

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'especialidad' => $this->faker->word,
        ];
    }
}


-----------------------------

4. Ejecutar los seeders

Ejecuta el siguiente comando para aplicar los seeders y poblar la base de datos con datos de prueba:

php artisan db:seed

Esto llenará tus tablas con los datos definidos en las fábricas.

Si hay algun problema haz php artisan migrate:fresh para comenzar con la base de datos vacía y luego php artisan db:seed

------------------------------

1. Uso de Tinker (OPCIONAL)

Tinker es la consola interactiva de Laravel que te permite ejecutar PHP y acceder a los modelos de tu aplicación en tiempo real. Es muy útil para:

    Probar relaciones y ver si están bien configuradas (por ejemplo, belongsTo, hasMany, belongsToMany, etc.).
    Crear/actualizar/buscar/eliminar datos sin tener que crear endpoints o interfaces.
    Depurar rápidamente sin escribir controladores de prueba.

1.1. Cómo iniciar Tinker

En la raíz de tu proyecto Laravel, ejecuta:

php artisan tinker

Aparecerá un prompt interactivo donde podrás ejecutar instrucciones PHP.
1.2. Ejemplos de uso
A) Consultar todos los registros de un modelo

App\Models\Estudiante::all();

Retorna una colección con todos los estudiantes.
B) Encontrar un registro por ID

$est = App\Models\Estudiante::find(1);
$est;

    $est contendrá el Estudiante con id=1 (o null si no existe).

C) Crear un nuevo registro

$est = new App\Models\Estudiante();
$est->nombre = 'Juan';
$est->apellidos = 'Pérez';
$est->email = 'juan@example.com';
$est->fecha_nacimiento = '2000-01-15';
$est->telefono = '123456789';
$est->save();

Esto guarda un nuevo Estudiante en la base de datos.
D) Actualizar un registro existente

$est = App\Models\Estudiante::find(1);
$est->telefono = '987654321';
$est->save();

Ahora el teléfono del estudiante con id=1 se actualiza.
E) Eliminar un registro

$est = App\Models\Estudiante::find(1);
$est->delete();

El estudiante con id=1 se elimina de la base de datos.
F) Probar relaciones

    Un curso y sus asignaturas (hasMany):

$curso = App\Models\Curso::find(1);
$curso->asignaturas;

Verás la colección de Asignaturas relacionadas con ese curso.

Un curso y su temporada (belongsTo):

$curso->temporada;

Observa la Temporada a la que pertenece el curso (relación 1:1 en tu diseño).

Un curso y sus estudiantes (belongsToMany):

$curso->estudiantes;

Devuelve la lista de Estudiantes que están asociados a ese curso a través de la tabla pivote curso_estudiante.

Un estudiante y sus cursos (belongsToMany):

    $est = App\Models\Estudiante::find(2);
    $est->cursos;

    Devuelve la colección de Cursos en los que está inscrito el estudiante 2.

Con estos ejemplos verás si las foreign keys y las relaciones Eloquent están correctamente configuradas.

------------------------------------------------

Después de realizar las pruebas básicas con Tinker y asegurarte de que las relaciones, modelos y migraciones funcionan correctamente, el siguiente paso es continuar con las siguientes áreas del proyecto:
1. Crear el controlador de la API

El controlador gestionará las solicitudes HTTP (GET, POST, PUT, DELETE) que recibirás para cada entidad (como Estudiantes, Cursos, etc.).

El comando más rápido es:

php artisan make:controller NombreController --api

    Crea un controlador para cada entidad:

    php artisan make:controller EstudianteController --api
    php artisan make:controller TemporadaController --api
    php artisan make:controller CursoController --api
    php artisan make:controller ProfesorController --api
    php artisan make:controller MatriculaController --api
    php artisan make:controller AsignaturaController --api
    php artisan make:controller EvaluacionController --api

Esto generará controladores con métodos básicos (index, store, show, update, destroy).

----------------------------------

3. Configurar los métodos en los controladores:

Cada controlador tendrá los métodos básicos. Debes rellenarlos para que realicen las operaciones correspondientes con los modelos.

2.1. EstudianteController

<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    // GET /api/estudiantes
    public function index()
    {
        return Estudiante::all();
    }

    // GET /api/estudiantes/{id}
    public function show($id)
    {
        return Estudiante::findOrFail($id);
    }

    // POST /api/estudiantes
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email|unique:estudiantes,email',
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'required|date',
        ]);

        return Estudiante::create($data);
    }

    // PUT/PATCH /api/estudiantes/{id}
    public function update(Request $request, $id)
    {
        $est = Estudiante::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'string',
            'apellidos' => 'string',
            'email' => 'email|unique:estudiantes,email,' . $id,
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'date',
        ]);

        $est->update($data);
        return $est;
    }

    // DELETE /api/estudiantes/{id}
    public function destroy($id)
    {
        Estudiante::destroy($id);
        return response()->json(['message' => 'Estudiante eliminado']);
    }
}

2.2. ProfesorController

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function index() {
        return Profesor::all();
    }

    public function show($id) {
        return Profesor::findOrFail($id);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email|unique:profesores,email',
            'telefono' => 'nullable|string',
            'especialidad' => 'required|string',
        ]);
        return Profesor::create($data);
    }

    public function update(Request $request, $id) {
        $prof = Profesor::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'string',
            'apellidos' => 'string',
            'email' => 'email|unique:profesores,email,' . $id,
            'telefono' => 'nullable|string',
            'especialidad' => 'string',
        ]);
        $prof->update($data);
        return $prof;
    }

    public function destroy($id) {
        Profesor::destroy($id);
        return response()->json(['message' => 'Profesor eliminado']);
    }
}

2.3. TemporadaController

(Si requieres CRUD de temporadas, también conviene crearlo)

namespace App\Http\Controllers;

use App\Models\Temporada;
use Illuminate\Http\Request;

class TemporadaController extends Controller
{
    public function index()
    {
        return Temporada::all();
    }

    public function show($id)
    {
        return Temporada::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        return Temporada::create($data);
    }

    public function update(Request $request, $id)
    {
        $temporada = Temporada::findOrFail($id);
        $data = $request->validate([
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date|after:fecha_inicio',
        ]);

        $temporada->update($data);
        return $temporada;
    }

    public function destroy($id)
    {
        Temporada::destroy($id);
        return response()->json(['message' => 'Temporada eliminada']);
    }
}

2.4. CursoController

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        return Curso::all();
    }

    public function show($id)
    {
        return Curso::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'id_temporada' => 'nullable|exists:temporadas,id', 
        ]);

        return Curso::create($data);
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'string',
            'descripcion' => 'string',
            'duracion' => 'integer|min:1',
            'id_temporada' => 'nullable|exists:temporadas,id',
        ]);

        $curso->update($data);
        return $curso;
    }

    public function destroy($id)
    {
        Curso::destroy($id);
        return response()->json(['message' => 'Curso eliminado']);
    }
}

2.5. AsignaturaController

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        return Asignatura::all();
    }

    public function show($id)
    {
        return Asignatura::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'curso_id' => 'required|exists:cursos,id',
            'profesor_id' => 'required|exists:profesores,id',
        ]);

        return Asignatura::create($data);
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'string',
            'curso_id' => 'exists:cursos,id',
            'profesor_id' => 'exists:profesores,id',
        ]);
        $asignatura->update($data);
        return $asignatura;
    }

    public function destroy($id)
    {
        Asignatura::destroy($id);
        return response()->json(['message' => 'Asignatura eliminada']);
    }
}

2.6. MatriculaController

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        return Matricula::all();
    }

    public function show($id)
    {
        return Matricula::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_matricula' => 'required|date'
        ]);

        return Matricula::create($data);
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $data = $request->validate([
            'curso_id' => 'exists:cursos,id',
            'estudiante_id' => 'exists:estudiantes,id',
            'fecha_matricula' => 'date'
        ]);

        $matricula->update($data);
        return $matricula;
    }

    public function destroy($id)
    {
        Matricula::destroy($id);
        return response()->json(['message' => 'Matrícula eliminada']);
    }
}

2.7. EvaluacionController

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    public function index()
    {
        return Evaluacion::all();
    }

    public function show($id)
    {
        return Evaluacion::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'curso_id' => 'required|exists:cursos,id',
            'nota' => 'required|numeric|between:0,10'
        ]);

        return Evaluacion::create($data);
    }

    public function update(Request $request, $id)
    {
        $ev = Evaluacion::findOrFail($id);
        $data = $request->validate([
            'estudiante_id' => 'exists:estudiantes,id',
            'asignatura_id' => 'exists:asignaturas,id',
            'curso_id' => 'exists:cursos,id',
            'nota' => 'numeric|between:0,10'
        ]);

        $ev->update($data);
        return $ev;
    }

    public function destroy($id)
    {
        Evaluacion::destroy($id);
        return response()->json(['message' => 'Evaluación eliminada']);
    }
}

(Opcional) 2.8. Controlador o métodos para la tabla pivote curso_estudiante

    Si necesitas asignar/desasignar estudiantes a cursos masivamente, podrías crear un CursoEstudianteController o usar métodos en CursoController.
    Por ejemplo, un método attachEstudiantes(Request $request, $idCurso) para hacer $curso->estudiantes()->attach([...]).





    2.9. Definir rutas en routes/api.php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\TemporadaController;

Route::apiResource('estudiantes', EstudianteController::class);
Route::apiResource('profesores', ProfesorController::class);
Route::apiResource('temporadas', TemporadaController::class);
Route::apiResource('cursos', CursoController::class);
Route::apiResource('asignaturas', AsignaturaController::class);
Route::apiResource('matriculas', MatriculaController::class);
Route::apiResource('evaluaciones', EvaluacionController::class);

// Si creas un controlador adicional para la tabla pivote:
// Route::apiResource('curso-estudiante', CursoEstudianteController::class);

Listo. Con esto, cada controlador se asocia a un conjunto de endpoints RESTful como GET /api/estudiantes, POST /api/estudiantes, etc.

----------

1. POSTMAN: Organización y Exportación
1.1. Crear una colección en Postman

    Abre Postman y haz clic en Collections (Colecciones).
    Haz clic en New Collection (Nueva Colección) y nómbrala, por ejemplo, IGFormacion API.
    Dentro de esa colección, irás creando Requests (solicitudes) para cada endpoint (GET, POST, PUT, DELETE).

1.2. Cómo exportar

    Cuando hayas terminado de configurar los endpoints, ve al menú de los tres puntos al lado de la colección.
    Selecciona Export.
    Escoge formato 2.1 (el más común).
    Guarda el archivo .json.
    Ese archivo podrás subirlo a tu repositorio o compartirlo con tu profesor/compañeros para que puedan importar la misma colección.

2. Endpoints por Entidad

A continuación, se listan las entidades habituales y sus endpoints REST (CRUD). Ten en cuenta que:

    El nombre del recurso (ej. /estudiantes, /profesores, etc.) viene de las rutas que definiste en api.php.
    En cada ejemplo, se supone que el servidor corre en http://127.0.0.1:8000 (por defecto al usar php artisan serve).

2.1. Temporadas

Esta tabla se relaciona 1:1 con cursos (un curso pertenece a una temporada, una temporada puede tener un curso).
Si has creado un TemporadaController, tendrás algo así:

    GET /api/temporadas
        Descripción: Obtiene todas las temporadas.
        Ejemplo:

    GET http://127.0.0.1:8000/api/temporadas

GET /api/temporadas/{id}

    Descripción: Obtiene una temporada específica.
    Ejemplo:

    GET http://127.0.0.1:8000/api/temporadas/1

POST /api/temporadas

    Descripción: Crea una nueva temporada.
    Cuerpo (Body) (JSON):

{
  "fecha_inicio": "2025-01-01",
  "fecha_fin": "2025-06-30"
}

Ejemplo:

    POST http://127.0.0.1:8000/api/temporadas

PUT /api/temporadas/{id}

    Descripción: Actualiza una temporada.
    Cuerpo (Body) (JSON):

{
  "fecha_inicio": "2025-02-01",
  "fecha_fin": "2025-07-01"
}

Ejemplo:

    PUT http://127.0.0.1:8000/api/temporadas/1

DELETE /api/temporadas/{id}

    Descripción: Elimina una temporada.
    Ejemplo:

        DELETE http://127.0.0.1:8000/api/temporadas/1

2.2. Estudiantes

    GET /api/estudiantes
        Descripción: Obtiene todos los estudiantes.
        Ejemplo:

    GET http://127.0.0.1:8000/api/estudiantes

GET /api/estudiantes/{id}

    Descripción: Obtiene un estudiante específico.
    Ejemplo:

    GET http://127.0.0.1:8000/api/estudiantes/1

POST /api/estudiantes

    Descripción: Crea un nuevo estudiante.
    Cuerpo (Body) (JSON):

{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan.perez@example.com",
  "telefono": "123456789",
  "fecha_nacimiento": "2000-05-15"
}

Ejemplo:

    POST http://127.0.0.1:8000/api/estudiantes

PUT /api/estudiantes/{id}

    Descripción: Actualiza un estudiante.
    Cuerpo (Body) (JSON):

{
  "nombre": "Juan Actualizado",
  "apellidos": "Pérez Gómez"
}

Ejemplo:

    PUT http://127.0.0.1:8000/api/estudiantes/1

DELETE /api/estudiantes/{id}

    Descripción: Elimina un estudiante.
    Ejemplo:

        DELETE http://127.0.0.1:8000/api/estudiantes/1

2.3. Cursos

    GET /api/cursos
        Descripción: Obtiene todos los cursos.
        Ejemplo:

    GET http://127.0.0.1:8000/api/cursos

GET /api/cursos/{id}

    Descripción: Obtiene un curso específico.
    Ejemplo:

    GET http://127.0.0.1:8000/api/cursos/2

POST /api/cursos

    Descripción: Crea un nuevo curso.
    Cuerpo (Body) (JSON):

{
  "nombre": "Curso Laravel",
  "descripcion": "Curso avanzado de Laravel",
  "duracion": 10,
  "id_temporada": 1
}

Ejemplo:

    POST http://127.0.0.1:8000/api/cursos

    Nota: si en tu diseño tenías fecha_inicio y fecha_fin directamente en cursos, ajusta el JSON en consecuencia.

PUT /api/cursos/{id}

    Descripción: Actualiza un curso.
    Cuerpo (Body) (JSON):

{
  "descripcion": "Curso Laravel actualizado",
  "duracion": 12
}

Ejemplo:

    PUT http://127.0.0.1:8000/api/cursos/2

DELETE /api/cursos/{id}

    Descripción: Elimina un curso.
    Ejemplo:

        DELETE http://127.0.0.1:8000/api/cursos/2

2.4. Profesores

    GET /api/profesores

GET http://127.0.0.1:8000/api/profesores

GET /api/profesores/{id}

GET http://127.0.0.1:8000/api/profesores/3

POST /api/profesores
Body (JSON):

{
  "nombre": "María",
  "apellidos": "López",
  "email": "maria.lopez@example.com",
  "telefono": "123456789",
  "especialidad": "Matemáticas"
}

POST http://127.0.0.1:8000/api/profesores

PUT /api/profesores/{id}
Body (JSON):

{
  "nombre": "María Actualizada",
  "apellidos": "López",
  "email": "maria.lopez@example.com",
  "telefono": "987654321",
  "especialidad": "Física"
}

PUT http://127.0.0.1:8000/api/profesores/3

DELETE /api/profesores/{id}

    DELETE http://127.0.0.1:8000/api/profesores/3

2.5. Asignaturas

    GET /api/asignaturas

GET http://127.0.0.1:8000/api/asignaturas

GET /api/asignaturas/{id}

GET http://127.0.0.1:8000/api/asignaturas/4

POST /api/asignaturas
Body (JSON):

{
  "nombre": "Álgebra",
  "curso_id": 2,
  "profesor_id": 3
}

POST http://127.0.0.1:8000/api/asignaturas

PUT /api/asignaturas/{id}
Body (JSON):

{
  "nombre": "Álgebra Avanzada",
  "curso_id": 2,
  "profesor_id": 3
}

PUT http://127.0.0.1:8000/api/asignaturas/4

DELETE /api/asignaturas/{id}

    DELETE http://127.0.0.1:8000/api/asignaturas/4

2.6. Matrículas

    GET /api/matriculas

GET http://127.0.0.1:8000/api/matriculas

GET /api/matriculas/{id}

GET http://127.0.0.1:8000/api/matriculas/5

POST /api/matriculas
Body (JSON):

{
  "curso_id": 2,
  "estudiante_id": 1,
  "fecha_matricula": "2025-01-19"
}

POST http://127.0.0.1:8000/api/matriculas

PUT /api/matriculas/{id}
Body (JSON):

{
  "curso_id": 2,
  "estudiante_id": 1,
  "fecha_matricula": "2025-01-20"
}

PUT http://127.0.0.1:8000/api/matriculas/5

DELETE /api/matriculas/{id}

    DELETE http://127.0.0.1:8000/api/matriculas/5

2.7. Evaluaciones

    GET /api/evaluaciones

GET http://127.0.0.1:8000/api/evaluaciones

GET /api/evaluaciones/{id}

GET http://127.0.0.1:8000/api/evaluaciones/6

POST /api/evaluaciones
Body (JSON):

{
  "estudiante_id": 1,
  "asignatura_id": 4,
  "curso_id": 2,
  "nota": 8.5
}

POST http://127.0.0.1:8000/api/evaluaciones

PUT /api/evaluaciones/{id}
Body (JSON):

{
  "estudiante_id": 1,
  "asignatura_id": 4,
  "curso_id": 2,
  "nota": 9.0
}

PUT http://127.0.0.1:8000/api/evaluaciones/6

DELETE /api/evaluaciones/{id}

    DELETE http://127.0.0.1:8000/api/evaluaciones/6

2.8. (Opcional) Tabla pivote curso_estudiante (N:M)

Si tu aplicación necesita endpoints específicos para la tabla pivote (curso_estudiante), podrías:

    Crear un CursoEstudianteController con métodos index, store, destroy, etc.
    O bien, manejar la asociación en un método dentro de CursoController o EstudianteController, usando Eloquent (attach, detach, sync).

Ejemplo: POST /api/curso-estudiante para inscribir varios estudiantes a un curso:

{
  "curso_id": 2,
  "estudiantes_ids": [1, 3, 5]
}

En tu controlador, harías algo así:

$curso = Curso::find($request->curso_id);
$curso->estudiantes()->attach($request->estudiantes_ids);

Esto inserta esos registros en la tabla pivote.
3. Pasos para Identificar Errores

    Revisar logs de Laravel
        Usa la terminal con tail -f storage/logs/laravel.log o un package tipo php artisan logs:tail.
    Ver respuestas de Postman
        Si recibes un 422 (Unprocessable Entity), revisa la validación de campos.
        Si recibes 500, revisa el log o la consola para ver el error completo.
    Chequea Migraciones y Seeders
        Asegúrate de haber hecho php artisan migrate:fresh y php artisan db:seed si estás probando datos iniciales.
    Ajustar data
        Si la FK no existe, te dará error 1452 (Cannot add or update a child row).
        Revisa que los IDs existan en tablas relacionadas antes de crear un registro (e.g., curso_id válido, estudiante_id válido, etc.).

4. Probar cada Endpoint

    Iniciar servidor de Laravel

php artisan serve

Probar en Postman

    Envía GET para listar o ver un único recurso.
    Envía POST con un Body (JSON) para crear.
    Envía PUT o PATCH con un Body (JSON) para actualizar.
    Envía DELETE para eliminar.
    Verifica las respuestas y comprueba que la BD cambie como esperas.









