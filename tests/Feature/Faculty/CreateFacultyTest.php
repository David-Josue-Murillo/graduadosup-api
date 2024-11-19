<?php

namespace Tests\Feature\Faculty;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateFacultyTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/faculties';
    private const JSON_RESPONSE = [
        'message',
        'data' => [[
            'id',
            'name',
            'total_careers',
            'careers' => [
                'id',
                'name'
            ]
        ]],
        'status',
        'errors'
    ];

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

    /** @test */
    public function must_register_a_new_record(): void
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
    public function it_cannot_be_regiter_a_record_duplicated(): void
    {
        $this->must_register_a_new_record();
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', '/api'.self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Registro duplicado'
        ]);
    }

    /** @test */
    public function the_name_field_must_be_required(): void
    {
        $data = $this->validGraduateData(['name' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo nombre es obligatorio.'
        ]);
    }

    /** @test */
    public function the_name_must_not_be_a_number(): void
    {
        $data = $this->validGraduateData(['name' => 202419191919]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultad debe ser una cadena de texto. (and 1 more error)'
        ]);
    }

    /** @test */
    public function the_name_must_be_exceed_15_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultads debe exceder los 10 caracteres.'
        ]);
    }

    /** @test */
    public function the_name_only_must_bealphanumeric_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'testtesttest12']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre de la facultad debe contener solo caracteres alfanuméricos.'
        ]);
    }
}