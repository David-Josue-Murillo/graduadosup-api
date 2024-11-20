<?php

namespace Tests\Feature\Faculty;

use App\Models\Faculty;
use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateFacultyTest extends TestCase
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
            'name' => 'Facultad de Administración de Pública',
        ], $overrides);
    }

    #[Test] public function it_updates_an_existing_faculty(): void
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

        $updateRecord = Faculty::find(1);
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
            'errors' => 'El campo nombre es obligatorio.'
        ]);
    }

    #[Test] public function it_requires_the_name_to_be_longer_than_ten_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultades debe exceder los 10 caracteres.'
        ]);
    }

    #[Test] public function it_requires_the_name_to_be_a_string(): void
    {
        $data = $this->validGraduateData(['name' => 'testtesttest12']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultad debe contener solo caracteres alfanuméricos.'
        ]);
    }
}