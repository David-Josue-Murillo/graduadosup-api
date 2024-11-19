<?php

namespace Tests\Feature\NumGraduateTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\CampuSeeder;
use Database\Seeders\CareerSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class CreateDataInNumGraduateTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/graduates';
    private const JSON_RESPONSE = [
        'message',
        'data' => [[
            'id',
            'quantity',
            'year',
            'campus' => [
                'id',
                'name'
            ],
            'career' => [
                'id',
                'name'
            ]
        ]
        ],
        'status',
        'errors'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            FacultySeeder::class,
            CampuSeeder::class,
            CareerSeeder::class
        ]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'quantity' => 100,
            'year' => now()->year,
            'campus_id' => 1,
            'career_id' => 1,
        ], $overrides);
    }

    /** @test */
    public function register_a_new_number_of_graduates(): void
    {
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'errors'
        ]);
    }

    /** @test */
    public function it_cannot_be_regiter_a_number_of_graduates_duplicated(): void
    {
        $this->register_a_new_number_of_graduates();
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Registro duplicado'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_be_required(): void
    {
        $data = $this->validGraduateData(['quantity' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad es obligatorio'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_be_a_integer(): void
    {
        $data = $this->validGraduateData(['quantity' => "11fgfg1"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número entero'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_be_a_positive_integer(): void
    {
        $data =$this->validGraduateData(['quantity' => -1]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número positivo'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_not_be_a_n_number(): void
    {
        $data = $this->validGraduateData(['quantity' => 10000]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad no puede ser mayor a 2999'
        ]);
    }

    /** @test */
    public function the_year_must_be_required(): void
    {
        $data = $this->validGraduateData(['year' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año es obligatorio'
        ]);
    }

    /** @test */
    public function the_year_must_be_a_integer(): void
    {
        $data = $this->validGraduateData(['year' => "2024sdsd"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser un número entero (and 1 more error)'
        ]);
    }

    /** @test */
    public function the_year_must_not_be_than_old(): void
    {
        $data = $this->validGraduateData(['year' => 1999]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser mayor o igual a 2018'
        ]);
    }

    /** @test */
    public function the_year_must_not_be_older_than_the_current_year(): void
    {
        $data = $this->validGraduateData(['year' => 2025]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser menor o igual a 2024'
        ]);
    }

    /** @test */
     public function the_campus_must_be_required(): void
    {
        $data = $this->validGraduateData(['campus_id' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id del campus es obligatorio'
        ]);
    }

    /** @test */
    public function the_campus_must_be_a_integer(): void
    {
       $data = $this->validGraduateData(['campus_id' => "a"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID del campus debe ser un número entero'
        ]);
    }

    /** @test */
    public function the_campus_must_exist(): void
    {
        $data = $this->validGraduateData(['campus_id' => "2024"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campus seleccionado no existe'
        ]);
    }

    /** @test */
    public function the_career_must_be_required(): void
    {
       $data = $this->validGraduateData(['career_id' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id de la carrera es obligatorio'
        ]);
    }

    /** @test */
    public function the_career_must_be_a_integer(): void
    {
       $data = $this->validGraduateData(['career_id' => "a"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID de la carrera debe ser un número entero'
        ]);
    }

    /** @test */
    public function the_career_must_exist(): void
    {
       $data = $this->validGraduateData(['career_id' => 200]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La carrera seleccionado no existe'
        ]);
    }
}
