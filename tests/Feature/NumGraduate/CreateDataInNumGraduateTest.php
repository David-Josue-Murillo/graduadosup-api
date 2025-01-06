<?php

namespace Tests\Feature\NumGraduateTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\CampuSeeder;
use Database\Seeders\CareerSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test] public function it_registers_a_new_num_graduates(): void
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

    #[Test] public function it_does_not_allow_duplicate_data(): void
    {
        $this->it_registers_a_new_num_graduates();
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Registro duplicado'
        ]);
    }

    #[Test] public function it_requires_the_quantity_num_graduates(): void
    {
        $data = $this->validGraduateData(['quantity' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad es obligatorio'
        ]);
    }

    #[Test] public function it_require_the_quantity_num_graduates_to_be_integer(): void
    {
        $data = $this->validGraduateData(['quantity' => "11fgfg1"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número entero'
        ]);
    }

    #[Test] public function it_require_the_quantity_num_graduates_to_be_a_positive_integer(): void
    {
        $data =$this->validGraduateData(['quantity' => -1]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número positivo'
        ]);
    }

    #[Test] public function it_require_the_quantity_graduates_to_be_validated_integer(): void
    {
        $data = $this->validGraduateData(['quantity' => 10000]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad no puede ser mayor a 2999'
        ]);
    }

    #[Test] public function it_requires_the_year_field(): void
    {
        $data = $this->validGraduateData(['year' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año es obligatorio'
        ]);
    }

    #[Test] public function it_require_the_year_to_be_an_integer(): void
    {
        $data = $this->validGraduateData(['year' => "2024sdsd"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser un número entero (and 1 more error)'
        ]);
    }



    #[Test] public function it_requires_the_year_to_not_exceed_current_year(): void
    {
        $data = $this->validGraduateData(['year' => 2026]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser menor o igual a ' . date('Y')
        ]);
    }

    #[Test] public function it_requires_the_campus_field(): void
    {
        $data = $this->validGraduateData(['campus_id' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id del campus es obligatorio'
        ]);
    }

    #[Test] public function it_requires_the_campus_id_to_be_an_integer(): void
    {
       $data = $this->validGraduateData(['campus_id' => "a"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID del campus debe ser un número entero'
        ]);
    }

    #[Test] public function it_requires_the_campus_id_to_exist(): void
    {
        $data = $this->validGraduateData(['campus_id' => "2024"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campus seleccionado no existe'
        ]);
    }

    #[Test] public function it_requires_the_career_field(): void
    {
       $data = $this->validGraduateData(['career_id' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id de la carrera es obligatorio'
        ]);
    }

    #[Test] public function it_requires_the_career_id_to_be_an_integer(): void
    {
       $data = $this->validGraduateData(['career_id' => "a"]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID de la carrera debe ser un número entero'
        ]);
    }

    #[Test] public function it_requires_the_career_id_to_exist(): void
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
