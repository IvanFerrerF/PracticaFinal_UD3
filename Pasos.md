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

-----------------------
1.3. Ejecutar el comando:

php artisan install:api

Esto debería haber generado el archivo api.php en el directorio routes y configurado el RouteServiceProvider correctamente para las rutas de la API.

1.4. Configurar el archivo .env para la conexión a la base de datos.


----------------------------


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


Modelos, recordar poner el campo $fillable

ara asegurarte de que todos tus modelos funcionen correctamente con los métodos POST y PUT, debes incluir la propiedad $fillable en cada uno de ellos. Aquí te dejo los modelos actualizados con los campos asignables masivamente:
Modelo Curso

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
        'fecha_inicio',
        'fecha_fin',
    ];

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

    protected $table = 'estudiantes'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'fecha_nacimiento',
    ];

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

Modelo Matricula

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

Modelo Evaluacion

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

Modelo Profesor

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


----------------------------------

8. Creación de Seeders para Poblar Datos de Prueba

Ahora vamos a generar datos de prueba para cada tabla usando los seeders de Laravel.
1. Generar los seeders para cada modelo

Ejecuta los siguientes comandos en la terminal para crear un seeder por cada tabla:

php artisan make:seeder EstudianteSeeder
php artisan make:seeder ProfesorSeeder
php artisan make:seeder CursoSeeder
php artisan make:seeder AsignaturaSeeder
php artisan make:seeder MatriculaSeeder
php artisan make:seeder EvaluacionSeeder

Esto generará los archivos en la carpeta database/seeders.

. Configurar cada seeder

A continuación, se configurarán los seeders. Aquí tienes ejemplos:
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


eeder de Profesores

Archivo: ProfesorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;

class ProfesorSeeder extends Seeder
{
    public function run()
    {
        Profesor::factory(5)->create(); // Crea 5 profesores con la fábrica
    }
}

Seeder de Cursos

Archivo: CursoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    public function run()
    {
        Curso::factory(3)->create(); // Crea 3 cursos con la fábrica
    }
}

Seeder de Asignaturas

Archivo: AsignaturaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        Asignatura::factory(15)->create(); // Crea 15 asignaturas con la fábrica
    }
}

Seeder de Matriculas

Archivo: MatriculaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matricula;

class MatriculaSeeder extends Seeder
{
    public function run()
    {
        Matricula::factory(20)->create(); // Crea 20 matrículas con la fábrica
    }
}

Seeder de Evaluaciones

Archivo: EvaluacionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluacion;

class EvaluacionSeeder extends Seeder
{
    public function run()
    {
        Evaluacion::factory(50)->create(); // Crea 50 evaluaciones con la fábrica
    }
}

----------------------------------


--------

. Registrar los seeders en DatabaseSeeder

Modifica el archivo DatabaseSeeder.php para incluir los seeders:

Archivo: DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            EstudianteSeeder::class,
            ProfesorSeeder::class,
            CursoSeeder::class,
            AsignaturaSeeder::class,
            MatriculaSeeder::class,
            EvaluacionSeeder::class,
        ]);
    }
}
---------------------
1. Generar las fábricas para los modelos

Ejecuta los siguientes comandos para crear las fábricas para cada modelo:

php artisan make:factory EstudianteFactory --model=Estudiante
php artisan make:factory ProfesorFactory --model=Profesor
php artisan make:factory CursoFactory --model=Curso
php artisan make:factory AsignaturaFactory --model=Asignatura
php artisan make:factory MatriculaFactory --model=Matricula
php artisan make:factory EvaluacionFactory --model=Evaluacion

Esto generará archivos en la carpeta database/factories.

----------------------------

. Configurar las fábricas

Modifica cada archivo generado en la carpeta database/factories para definir cómo se generan los datos. Aquí tienes ejemplos:
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


CursoFactory

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph,
            'duracion' => $this->faker->numberBetween(1, 52),
            'fecha_inicio' => $this->faker->date('Y-m-d'),
            'fecha_fin' => $this->faker->date('Y-m-d', '+6 months'),
        ];
    }
}

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AsignaturaFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'curso_id' => \App\Models\Curso::factory(), // Relación con Curso
            'profesor_id' => \App\Models\Profesor::factory(), // Relación con Profesor
        ];
    }
}

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MatriculaFactory extends Factory
{
    public function definition()
    {
        return [
            'curso_id' => \App\Models\Curso::factory(),
            'estudiante_id' => \App\Models\Estudiante::factory(),
            'fecha_matricula' => $this->faker->date('Y-m-d'),
        ];
    }
}


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluacionFactory extends Factory
{
    public function definition()
    {
        return [
            'estudiante_id' => \App\Models\Estudiante::factory(),
            'asignatura_id' => \App\Models\Asignatura::factory(),
            'curso_id' => \App\Models\Curso::factory(),
            'nota' => $this->faker->randomFloat(2, 0, 10), // Nota entre 0 y 10 con 2 decimales
        ];
    }
}
---------------------------
he tenido que retocar los factory

1. Volver a aplicar las migraciones

Para asegurarte de que todo esté limpio en la base de datos, ejecuta:

php artisan migrate:fresh

Esto:

    Eliminará todas las tablas existentes.
    Creará las tablas desde cero basándose en las migraciones.
-----------------------------

4. Ejecutar los seeders

Ejecuta el siguiente comando para aplicar los seeders y poblar la base de datos con datos de prueba:

php artisan db:seed

Esto llenará tus tablas con los datos definidos en las fábricas.

------------------------------

Me dispongo a usar Tinker 

Tinker es más útil para pruebas internas de tu código Laravel (modelos, relaciones, seeders), mientras que Postman es ideal para pruebas externas de tu API. Ambas herramientas son complementarias y juntas garantizan que tu aplicación esté funcionando correctamente en todos los niveles.

Probar las relaciones con Tinker no es algo obligatorio en un entorno profesional, pero es muy importante para garantizar que las relaciones entre los modelos estén configuradas correctamente. A nivel profesional, usar Tinker para pruebas rápidas ofrece varias ventajas:
Importancia de probar relaciones con Tinker

    Identificar problemas de configuración tempranamente:
        Si las relaciones entre los modelos (hasMany, belongsTo, etc.) no están configuradas correctamente, los errores surgirán en etapas avanzadas del desarrollo, lo que puede ser costoso en tiempo y esfuerzo. Con Tinker puedes identificar problemas inmediatamente.

    Pruebas rápidas y directas:
        En lugar de implementar interfaces o endpoints desde el principio, Tinker permite probar directamente los modelos y las relaciones sin escribir código adicional.

    Mejor comprensión de los datos:
        Trabajar con relaciones en Tinker te da una vista clara de cómo los datos se conectan y fluyen entre las tablas.

    Evitar errores en endpoints:
        Si las relaciones no funcionan como se espera, los endpoints que dependen de ellas (como los de una API REST) también fallarán. Con Tinker puedes validar todo antes de escribir los controladores o rutas.

    Fomenta un desarrollo más profesional:
        Las pruebas iterativas y rápidas con herramientas como Tinker son una práctica estándar en entornos profesionales, donde se prioriza la calidad y eficiencia del código.

Paso 1: Iniciar Tinker

Ejecuta el comando:

php artisan tinker

Esto abrirá una consola interactiva de Laravel, donde puedes ejecutar comandos PHP directamente.


Paso 2: Probar relaciones

Desde Tinker, puedes ejecutar comandos para interactuar con tus modelos y validar las relaciones:

    Ejemplo básico: Obtener datos de un modelo:

$curso = App\Models\Curso::find(1);
$curso;

Esto devuelve el curso con id = 1.

Probar relación hasMany (por ejemplo, un curso tiene muchas asignaturas):

$curso = App\Models\Curso::find(1);
$curso->asignaturas;

Esto devolverá una colección de las asignaturas relacionadas con el curso.

Probar relación belongsTo (por ejemplo, una asignatura pertenece a un curso):

$asignatura = App\Models\Asignatura::find(1);
$asignatura->curso;

Esto devolverá el curso al que pertenece la asignatura.

Probar relaciones más complejas (with): Puedes cargar relaciones anidadas:

$cursos = App\Models\Curso::with('asignaturas.evaluaciones')->get();
$cursos->toArray();

Esto devolverá todos los cursos, cada uno con sus asignaturas y las evaluaciones de esas asignaturas.


La lista proporcionada es solo un punto de partida. Según los requerimientos de tu proyecto, deberías personalizar las pruebas para asegurarte de que las relaciones y la lógica de tu base de datos están alineadas con lo que necesitas.

uándo saber que terminaste las pruebas

Sabes que has terminado de probar cuando:

    Relaciones básicas: Todas las relaciones entre modelos (hasMany, belongsTo, belongsToMany) funcionan como esperas.
    Restricciones: Las claves foráneas y las reglas de eliminación (cascade, set null) se respetan.
    Consultas: Puedes realizar las consultas que necesitas para tu aplicación sin errores.
    Datos dinámicos: Crear, actualizar y eliminar datos funciona correctamente con las relaciones definidas.

------------------------------------------------

Después de realizar las pruebas básicas con Tinker y asegurarte de que las relaciones, modelos y migraciones funcionan correctamente, el siguiente paso es continuar con las siguientes áreas del proyecto:
1. Crear el controlador de la API

El controlador gestionará las solicitudes HTTP (GET, POST, PUT, DELETE) que recibirás para cada entidad (como Estudiantes, Cursos, etc.).

    Crea un controlador para cada entidad:

    php artisan make:controller EstudianteController --api
    php artisan make:controller CursoController --api
    php artisan make:controller ProfesorController --api
    php artisan make:controller MatriculaController --api
    php artisan make:controller AsignaturaController --api
    php artisan make:controller EvaluacionController --api

Esto generará controladores con métodos básicos (index, store, show, update, destroy).

----------------------------------

3. Configurar los métodos en los controladores:

Cada controlador tendrá los métodos básicos. Debes rellenarlos para que realicen las operaciones correspondientes con los modelos.

Por ejemplo, en EstudianteController.php:

<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    // Mostrar todos los estudiantes
    public function index()
    {
        return Estudiante::all();
    }

    // Mostrar un estudiante específico
    public function show($id)
    {
        return Estudiante::find($id);
    }

    // Crear un nuevo estudiante
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

    // Actualizar un estudiante
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'string',
            'apellidos' => 'string',
            'email' => 'email|unique:estudiantes,email,' . $id,
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'date',
        ]);

        $estudiante = Estudiante::find($id);
        $estudiante->update($data);

        return $estudiante;
    }

    // Eliminar un estudiante
    public function destroy($id)
    {
        Estudiante::destroy($id);
        return response()->json(['message' => 'Estudiante eliminado']);
    }
}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    public function index()
    {
        return Curso::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        return Curso::create($validated);
    }

    public function show($id)
    {
        return Curso::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'descripcion' => 'string',
            'duracion' => 'integer|min:1',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date|after:fecha_inicio',
        ]);

        $curso->update($validated);

        return $curso;
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        return response()->json(['message' => 'Curso eliminado'], 200);
    }
}


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;

class ProfesorController extends Controller
{
    public function index()
    {
        return Profesor::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:profesores,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:255',
        ]);

        return Profesor::create($validated);
    }

    public function show($id)
    {
        return Profesor::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $profesor = Profesor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'apellidos' => 'string|max:255',
            'email' => 'email|unique:profesores,email,' . $id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'string|max:255',
        ]);

        $profesor->update($validated);

        return $profesor;
    }

    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);
        $profesor->delete();

        return response()->json(['message' => 'Profesor eliminado'], 200);
    }
}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignatura;

class AsignaturaController extends Controller
{
    public function index()
    {
        return Asignatura::all(); // Devuelve todas las asignaturas
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id', // Relación con Curso
            'profesor_id' => 'required|exists:profesores,id', // Relación con Profesor
        ]);

        return Asignatura::create($validated);
    }

    public function show($id)
    {
        return Asignatura::findOrFail($id); // Devuelve una asignatura específica
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'curso_id' => 'exists:cursos,id',
            'profesor_id' => 'exists:profesores,id',
        ]);

        $asignatura->update($validated);

        return $asignatura;
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $asignatura->delete();

        return response()->json(['message' => 'Asignatura eliminada'], 200);
    }
}


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;

class MatriculaController extends Controller
{
    public function index()
    {
        return Matricula::all(); // Devuelve todas las matrículas
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id', // Relación con Curso
            'estudiante_id' => 'required|exists:estudiantes,id', // Relación con Estudiante
            'fecha_matricula' => 'required|date',
        ]);

        return Matricula::create($validated);
    }

    public function show($id)
    {
        return Matricula::findOrFail($id); // Devuelve una matrícula específica
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $validated = $request->validate([
            'curso_id' => 'exists:cursos,id',
            'estudiante_id' => 'exists:estudiantes,id',
            'fecha_matricula' => 'date',
        ]);

        $matricula->update($validated);

        return $matricula;
    }

    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->delete();

        return response()->json(['message' => 'Matrícula eliminada'], 200);
    }
}


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;

class EvaluacionController extends Controller
{
    public function index()
    {
        return Evaluacion::all(); // Devuelve todas las evaluaciones
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id', // Relación con Estudiante
            'asignatura_id' => 'required|exists:asignaturas,id', // Relación con Asignatura
            'curso_id' => 'required|exists:cursos,id', // Relación con Curso
            'nota' => 'required|numeric|between:0,10', // Nota entre 0 y 10
        ]);

        return Evaluacion::create($validated);
    }

    public function show($id)
    {
        return Evaluacion::findOrFail($id); // Devuelve una evaluación específica
    }

    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        $validated = $request->validate([
            'estudiante_id' => 'exists:estudiantes,id',
            'asignatura_id' => 'exists:asignaturas,id',
            'curso_id' => 'exists:cursos,id',
            'nota' => 'numeric|between:0,10',
        ]);

        $evaluacion->update($validated);

        return $evaluacion;
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return response()->json(['message' => 'Evaluación eliminada'], 200);
    }
}


Pasos a seguir después de crear los controladores 
1. Configurar rutas en api.php:

// Rutas para los controladores
Route::apiResource('estudiantes', \App\Http\Controllers\EstudianteController::class);
Route::apiResource('cursos', \App\Http\Controllers\CursoController::class);
Route::apiResource('profesores', \App\Http\Controllers\ProfesorController::class);
Route::apiResource('matriculas', \App\Http\Controllers\MatriculaController::class);
Route::apiResource('asignaturas', \App\Http\Controllers\AsignaturaController::class);
Route::apiResource('evaluaciones', \App\Http\Controllers\EvaluacionController::class);

----------

Configuración de Postman

    Crear una colección:
        Abre Postman y crea una nueva colección con el nombre: IGFormacion API.

    Añadir los endpoints a la colección:
        Para cada entidad (estudiantes, cursos, profesores, matriculas, asignaturas, evaluaciones), añade solicitudes con los siguientes detalles:
        

        creo recordar que para el primero, estudiantes por ejemplo usamos id 1, para el segundo por ejemplo asignatura el id 2, asi en adelante


Pasos para identificar el error

    Revisar los logs de Laravel:
        En la terminal, ejecuta:

    php artisan logs:tail

    Esto mostrará detalles sobre el error que se está produciendo.

O revisa manualmente el archivo de logs:

storage/logs/laravel.log








Estudiantes

    GET /api/estudiantes
        Descripción: Obtiene todos los estudiantes.
        Método: GET
        URL: http://127.0.0.1:8000/api/estudiantes

    POST /api/estudiantes
        Descripción: Crea un nuevo estudiante.
        Método: POST
        URL: http://127.0.0.1:8000/api/estudiantes
        Cuerpo (Body):

    {
        "nombre": "Juan",
        "apellidos": "Pérez",
        "email": "juan.perez@example.com",
        "telefono": "123456789",
        "fecha_nacimiento": "2000-05-15"
    }

GET /api/estudiantes/{id}

    Descripción: Obtiene un estudiante específico.
    Método: GET
    URL: http://127.0.0.1:8000/api/estudiantes/1

PUT /api/estudiantes/{id}

    Descripción: Actualiza un estudiante.
    Método: PUT
    URL: http://127.0.0.1:8000/api/estudiantes/1
    Cuerpo (Body):

        {
            "nombre": "Juan Actualizado",
            "apellidos": "Pérez Gómez"
        }

    DELETE /api/estudiantes/{id}
        Descripción: Elimina un estudiante.
        Método: DELETE
        URL: http://127.0.0.1:8000/api/estudiantes/1

Cursos

    GET /api/cursos
        Descripción: Obtiene todos los cursos.
        Método: GET
        URL: http://127.0.0.1:8000/api/cursos

    POST /api/cursos
        Descripción: Crea un nuevo curso.
        Método: POST
        URL: http://127.0.0.1:8000/api/cursos
        Cuerpo (Body):

    {
        "nombre": "Curso Laravel",
        "descripcion": "Curso avanzado de Laravel",
        "duracion": 10,
        "fecha_inicio": "2025-01-01",
        "fecha_fin": "2025-03-01"
    }

GET /api/cursos/{id}

    Descripción: Obtiene un curso específico.
    Método: GET
    URL: http://127.0.0.1:8000/api/cursos/2

PUT /api/cursos/{id}

    Descripción: Actualiza un curso.
    Método: PUT
    URL: http://127.0.0.1:8000/api/cursos/2
    Cuerpo (Body):

        {
            "descripcion": "Curso Laravel actualizado",
            "duracion": 12
        }

    DELETE /api/cursos/{id}
        Descripción: Elimina un curso.
        Método: DELETE
        URL: http://127.0.0.1:8000/api/cursos/2

1. Profesores
POST: Crear un profesor

    URL: http://127.0.0.1:8000/api/profesores
    Body (JSON):

    {
      "nombre": "María",
      "apellidos": "López",
      "email": "maria.lopez@example.com",
      "telefono": "123456789",
      "especialidad": "Matemáticas"
    }

GET: Listar todos los profesores

    URL: http://127.0.0.1:8000/api/profesores

GET: Mostrar un profesor específico

    URL: http://127.0.0.1:8000/api/profesores/3

PUT: Actualizar un profesor

    URL: http://127.0.0.1:8000/api/profesores/3
    Body (JSON):

    {
      "nombre": "María Actualizada",
      "apellidos": "López",
      "email": "maria.lopez@example.com",
      "telefono": "987654321",
      "especialidad": "Física"
    }

DELETE: Eliminar un profesor

    URL: http://127.0.0.1:8000/api/profesores/3

2. Asignaturas
POST: Crear una asignatura

    URL: http://127.0.0.1:8000/api/asignaturas
    Body (JSON):

    {
      "nombre": "Álgebra",
      "curso_id": 2,
      "profesor_id": 3
    }

GET: Listar todas las asignaturas

    URL: http://127.0.0.1:8000/api/asignaturas

GET: Mostrar una asignatura específica

    URL: http://127.0.0.1:8000/api/asignaturas/4

PUT: Actualizar una asignatura

    URL: http://127.0.0.1:8000/api/asignaturas/4
    Body (JSON):

    {
      "nombre": "Álgebra Avanzada",
      "curso_id": 2,
      "profesor_id": 3
    }

DELETE: Eliminar una asignatura

    URL: http://127.0.0.1:8000/api/asignaturas/4

3. Matrículas
POST: Crear una matrícula

    URL: http://127.0.0.1:8000/api/matriculas
    Body (JSON):

    {
      "curso_id": 2,
      "estudiante_id": 1,
      "fecha_matricula": "2025-01-19"
    }

GET: Listar todas las matrículas

    URL: http://127.0.0.1:8000/api/matriculas

GET: Mostrar una matrícula específica

    URL: http://127.0.0.1:8000/api/matriculas/5

PUT: Actualizar una matrícula

    URL: http://127.0.0.1:8000/api/matriculas/5
    Body (JSON):

    {
      "curso_id": 2,
      "estudiante_id": 1,
      "fecha_matricula": "2025-01-20"
    }

DELETE: Eliminar una matrícula

    URL: http://127.0.0.1:8000/api/matriculas/5

4. Evaluaciones
POST: Crear una evaluación

    URL: http://127.0.0.1:8000/api/evaluaciones
    Body (JSON):

    {
      "estudiante_id": 1,
      "asignatura_id": 4,
      "curso_id": 2,
      "nota": 8.5
    }

GET: Listar todas las evaluaciones

    URL: http://127.0.0.1:8000/api/evaluaciones

GET: Mostrar una evaluación específica

    URL: http://127.0.0.1:8000/api/evaluaciones/6

PUT: Actualizar una evaluación

    URL: http://127.0.0.1:8000/api/evaluaciones/6
    Body (JSON):

    {
      "estudiante_id": 1,
      "asignatura_id": 4,
      "curso_id": 2,
      "nota": 9.0
    }

DELETE: Eliminar una evaluación

    URL: http://127.0.0.1:8000/api/evaluaciones/6

Probar los endpoints

    Iniciar el servidor:
        Asegúrate de que Laravel está ejecutándose:

        php artisan serve

    Probar cada endpoint en Postman:
        Envía solicitudes a cada endpoint y verifica las respuestas.
        Ajusta los datos según sea necesario para probar validaciones y errores.

Pasos para exportar:

    En Postman, ve a la colección que usaste para probar tus endpoints.
    Haz clic en los tres puntos al lado del nombre de la colección.
    Selecciona Export.
    Escoge el formato v2.1 (preferible para compatibilidad).
    Guarda el archivo .json generado en el directorio de tu proyecto.


    ========================









