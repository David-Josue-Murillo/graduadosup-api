<?php

namespace Tests\Feature\Career;

use App\Models\Career;
use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateRecordCareerTest extends TestCase
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
            'id' => 1,
            'name' => 'Técnico en Informática',
            'faculty_id' => 1,
        ], $overrides);
    }

    #[Test] public function it_updates_an_existing_career(): void
    {
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'errors'
        ]);

        $updateRecord = Career::find(1);
        foreach($this->validGraduateData() as $key => $value) {
            $this->assertEquals($value, $updateRecord->$key, "El campo '$key' no coincide.");
        }
    }

    #[Test] public function it_requires_the_name_field(): void
    {
        $data = $this->validGraduateData(['name' => '']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo nombre de la carrera es obligatorio.'
        ]);
    }

    #[Test] public function it_requires_the_name_to_be_a_string(): void
    {
        $data = $this->validGraduateData(['name' => 202419191919]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre debe contener solo caracteres alfanuméricos.'
        ]);
    }

    #[Test] public function it_requires_the_name_to_be_longer_than_ten_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre debe exceder los 10 caracteres.'
        ]);
    }

    #[Test] public function it_requires_the_faculty_id_field(): void
    {
        $data = $this->validGraduateData(['faculty_id' => '']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo facultad es obligatorio.'
        ]);
    }

    #[Test] public function it_requires_the_faculty_id_to_be_an_integer(): void
    {
        $data = $this->validGraduateData(['faculty_id' => 'test']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID de la facultad debe ser un número entero.'
        ]);
    }

    #[Test] public function it_requires_the_faculty_id_to_exist(): void
    {
        $data = $this->validGraduateData(['faculty_id' => 20]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La facultad seleccionada no existe.'
        ]);
    }
}
