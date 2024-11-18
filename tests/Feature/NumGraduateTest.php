<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CampuSeeder;
use Database\Seeders\CareerSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumGraduateTest extends TestCase
{
    use RefreshDatabase;
    protected $jsonResponseEstructure;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jsonResponseEstructure = [
            'message',
            'data' => [
                'id',
                'quantity',
                'year',
                'campus' => [
                    'id',
                    'name'
                ],
                'career' => [
                    'id',
                    'name',
                    'faculty' => [
                        'id',
                        'name'
                    ]
                ]
            ],
            'status'
        ];
        $this->seed([
            UserSeeder::class,
            FacultySeeder::class,
            CampuSeeder::class,
            CareerSeeder::class
        ]);
    }

    /** @test */
    public function regiter_a_new_number_of_graduates(): void
    {
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 1
        ];

        $response = $this->apiAs(User::find(1), 'post', '/graduates', $data);
        
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
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 1
        ];

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
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 1
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad es obligatorio'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_be_a_integer(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => "11fgfg1",
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número entero'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_be_a_positive_integer(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => -1,
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número postivo'
        ]);
    }

    /** @test */
    public function the_quantity_graduates_must_not_be_a_n_number(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 10000,
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad no puede ser mayor a 2999'
        ]);
    }

    /** @test */
    public function the_year_must_be_required(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año es obligatorio'
        ]);
    }

    /** @test */
    public function the_year_must_be_a_integer(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => "2024sdsd",
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser un número entero (and 1 more error)'
        ]);
    }

    /** @test */
    public function the_year_must_not_be_than_old(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => '1999',
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser mayor o igual a 2018'
        ]);
    }

    /** @test */
    public function the_year_must_not_be_older_than_the_current_year(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => 2025,
            'campus_id' => 1,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser menor o igual a 2024'
        ]);
    }

    /** @test */
     public function the_campus_must_be_required(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id del campus es obligatorio'
        ]);
    }
 
    /** @test */
    public function the_campus_must_be_a_integer(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => "2024",
            'campus_id' => "a",
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID del campus debe ser un número entero'
        ]);
    }

    /** @test */
    public function the_campus_must_exist(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => "2024",
            'campus_id' => 100,
            'career_id' => 2
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campus seleccionado no existe'
        ]);
    }

    /** @test */
    public function the_career_must_be_required(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 1,
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id de la carrera es obligatorio'
        ]);
    }
 
    /** @test */
    public function the_career_must_be_a_integer(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => "2024",
            'campus_id' => 1,
            'career_id' => "a"
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID de la carrera debe ser un número entero'
        ]);
    }

    /** @test */
    public function the_career_must_exist(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => "2024",
            'campus_id' => 1,
            'career_id' => 200
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La carrera seleccionado no existe'
        ]);
    }
}
