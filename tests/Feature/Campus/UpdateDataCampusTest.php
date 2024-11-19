<?php

namespace Tests\Feature\Campus;

use App\Models\Campu;
use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateDataCampusTest extends TestCase
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
            'id' => 1,
            'name' => 'Centro regional universitario de Veraguas',
        ], $overrides);
    }

    /** @test */
    public function must_update_a_exist_record(): void
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

        $updateRecord = Campu::find(1);
        foreach($this->validGraduateData() as $key => $value) {
            $this->assertEquals($value, $updateRecord->$key, "El campo '$key' no coincide.");
        }
    }

    /** @test */
    public function the_name_field_must_be_required(): void
    {
        $data = $this->validGraduateData(['name' => '']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo nombre es obligatorio'
        ]);
    }

    /** @test */
    public function the_name_must_not_be_a_number(): void
    {
        $data = $this->validGraduateData(['name' => 202419191919]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre del campus debe ser una cadena de texto. (and 1 more error)'
        ]);
    }

    /** @test */
    public function the_name_must_be_exceed_15_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'test']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre del campus debe exceder los 10 caracteres.'
        ]);
    }

    /** @test */
    public function the_name_only_must_bealphanumeric_characters(): void
    {
        $data = $this->validGraduateData(['name' => 'testtesttest12']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre del campus debe contener solo caracteres alfanuméricos.'
        ]);
    }
}
