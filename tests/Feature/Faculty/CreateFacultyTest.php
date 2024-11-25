<?php

namespace Tests\Feature\Faculty;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateFacultyTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/faculties';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'id' => 4,
            'name' => 'Facultad de Administración de Pública',
        ], $overrides);
    }

    #[Test] public function it_registers_a_new_faculty(): void
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

    #[Test] public function it_does_not_allow_duplicate_faculty_names(): void
    {
        $this->it_registers_a_new_faculty();
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', '/api'.self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Registro duplicado'
        ]);
    }

    #[Test] public function it_requires_the_name_field(): void
    {
        $data = $this->validGraduateData(['name' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo nombre es obligatorio.'
        ]);
    }

    #[Test] public function it_name_must_be_exceed_ten_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultades debe exceder los 10 caracteres.'
        ]);
    }

    #[Test] public function it_requires_the_name_to_be_a_string(): void
    {
        $data = $this->validGraduateData(['name' => 'test test 12']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultad debe contener solo caracteres alfanuméricos.'
        ]);
    }
}