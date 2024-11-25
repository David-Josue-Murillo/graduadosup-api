<?php

namespace Tests\Feature\Career;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateDataCareerTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/careers';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'id' => 6,
            'name' => 'Licenciatura en enfermería',
            'faculty_id' => 3,
        ], $overrides);
    }

    #[Test] public function it_registers_a_new_caree(): void
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

    #[Test] public function it_does_not_allow_duplicate_career_names(): void
    {
        $this->it_registers_a_new_caree();
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', '/api'.self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Ya existe una carrera con este nombre.'
        ]);
    }

    #[Test] public function it_requires_the_name_field(): void
    {
        $data = $this->validGraduateData(['name' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo nombre de la carrera es obligatorio.'
        ]);
    }

    #[Test] public function it_requires_the_name_to_be_a_string(): void
    {
        $data = $this->validGraduateData(['name' => 202419191919]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre debe contener solo caracteres alfanuméricos.'
        ]);
    }

    #[Test] public function it_name_must_be_exceed_ten_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre debe exceder los 10 caracteres.'
        ]);
    }

    #[Test] public function it_requires_the_faculty_id_field(): void
    {
        $data = $this->validGraduateData(['faculty_id' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo facultad es obligatorio.'
        ]);
    }

    #[Test] public function it_requires_the_faculty_id_to_be_a_valid_number(): void
    {
        $data = $this->validGraduateData(['faculty_id' => 'test']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID de la facultad debe ser un número entero.'
        ]);
    }

    #[Test] public function it_requires_the_faculty_id_to_exist(): void
    {
        $data = $this->validGraduateData(['faculty_id' => 100]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La facultad seleccionada no existe.'
        ]);
    }
}
