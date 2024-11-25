<?php

namespace Tests\Feature\NumGraduateTest;

use App\Models\NumGraduate;
use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateRecordNumGraduateTest extends TestCase
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
            NumGraduateSeeder::class
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

    #[Test] public function it_updates_an_existing_num_graduates(): void
    {
        $this->validGraduateData([
            'quantity' => 10,
            'year' => 2024,
            'campus_id' => 2,
            'career_id' => 4
        ]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $this->validGraduateData());
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'errors'
        ]);

        $updateRecord = NumGraduate::find(1);
        foreach($this->validGraduateData() as $key => $value) {
            $this->assertEquals($value, $updateRecord->$key, "El campo '$key' no coincide.");
        }
    }

    #[Test] public function it_only_update_a_piece_of_data_in_the_record(): void
    {
        $this->validGraduateData(['year' => 2024]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $this->validGraduateData());
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'errors'
        ]);

        $updateRecord = NumGraduate::find(1);
        foreach($this->validGraduateData() as $key => $value) {
            $this->assertEquals($value, $updateRecord->$key, "El campo '$key' no coincide.");
        }
    }


    #[Test] public function it_fails_update_a_record_without_the_quantity_field(): void
    {
        $data = [
            'year' => now()->year,
            'campus_id' => 1,
            'career_id' => 1,
        ];

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);
        
        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors'])
            ->assertJsonFragment([
                'errors' => 'La cantidad es obligatorio'
            ]);
        
    }

    #[Test] public function it_fails_update_the_quantity_field_with_invalidate_data(): void
    {
        $data = [
            'quantity' => 'sas',
            'year' => now()->year,
            'campus_id' => 1,
            'career_id' => 1,
        ];

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número entero'
        ]);
    }

    #[Test] public function it_fails_update_the_quantity_field_if_the_number_is_negative(): void
    {
        $data =$this->validGraduateData(['quantity' => -1]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad debe ser un número positivo'
        ]);
    }

    #[Test] public function it_fails_update_the_quantity_field_if_the_number_exceeds_the_maximum_allowed(): void
    {
        $data = $this->validGraduateData(['quantity' => 10000]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La cantidad no puede ser mayor a 2999'
        ]);
    }

    #[Test] public function it_fails_update_a_record_without_the_year_field(): void
    {
        $data = $this->validGraduateData(['year' => '']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año es obligatorio'
        ]);
    }

    #[Test] public function it_fails_update_the_year_field_with_invalidate_data(): void
    {
        $data = $this->validGraduateData(['year' => "2024sdsd"]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser un número entero (and 1 more error)'
        ]);
    }

    #[Test] public function it_fails_update_the_quantity_field_if_the_number_is_less_than_allowed(): void
    {
        $data = $this->validGraduateData(['year' => 1999]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser mayor o igual a 2018'
        ]);
    }

    #[Test] public function it_fails_update_the_quantity_field_if_the_number_is_greater_than_allowed(): void
    {
        $data = $this->validGraduateData(['year' => 2025]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El año debe ser menor o igual a 2024'
        ]);
    }

    #[Test] public function it_fails_update_a_record_without_the_campus_id_fiel(): void
    {
        $data = $this->validGraduateData(['campus_id' => '']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id del campus es obligatorio'
        ]);
    }

    #[Test] public function it_fails_update_the_campus_field_with_invalidate_data(): void
    {
       $data = $this->validGraduateData(['campus_id' => "a"]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID del campus debe ser un número entero'
        ]);
    }

    #[Test] public function it_fails_update_a_record_if_not_exit_campus_id(): void
    {
        $data = $this->validGraduateData(['campus_id' => "2024"]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El campus seleccionado no existe'
        ]);
    }

    #[Test] public function it_fails_update_a_record_without_the_career_id_fiel(): void
    {
       $data = $this->validGraduateData(['career_id' => '']);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El id de la carrera es obligatorio'
        ]);
    }

    #[Test] public function it_fails_update_the_career_field_with_invalidate_data(): void
    {
       $data = $this->validGraduateData(['career_id' => "a"]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El ID de la carrera debe ser un número entero'
        ]);
    }

    #[Test] public function it_fails_update_a_record_if_not_exit_career_id(): void
    {
       $data = $this->validGraduateData(['career_id' => 200]);

        $response = $this->apiAs(User::find(1), 'put', self::URL.'/1', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La carrera seleccionado no existe'
        ]);
    }
}

