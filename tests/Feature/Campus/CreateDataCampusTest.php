<?php

namespace Tests\Feature\Campus;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateDataCampusTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/campus';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'id' => 3,
            'name' => 'Centro regional universitario de Chiriqui',
        ], $overrides);
    }

    #[Test] public function must_register_a_new_record(): void
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

    #[Test] public function it_cannot_be_regiter_a_record_duplicated(): void
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

    #[Test] public function the_name_field_must_be_required(): void
    {
        $data = $this->validGraduateData(['name' => '']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo nombre es obligatorio'
        ]);
    }

    #[Test] public function the_name_must_not_be_a_number(): void
    {
        $data = $this->validGraduateData(['name' => 202419191919]);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre del campus debe ser una cadena de texto. (and 1 more error)'
        ]);
    }

    #[Test] public function the_name_must_be_exceed_15_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre del campus debe exceder los 10 caracteres.'
        ]);
    }

    #[Test] public function the_name_only_must_bealphanumeric_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'testtesttest12']);

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre del campus debe contener solo caracteres alfanuméricos.'
        ]);
    }
}
