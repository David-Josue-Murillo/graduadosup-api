<?php

namespace Tests\Feature;

use App\Models\Faculty;
use Database\Seeders\FacultySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertJson;

class FacultyTest extends TestCase
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
                'name',
                'total_careers',
                'careers' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ],
            'status'
        ];
        $this->seed([
            FacultySeeder::class,
        ]);
    }

    /** @test */
    public function register_a_new_faculty_with_valid_data()
    {
        $response = $this->postJson('/faculties', [
            'name' => 'Facultad de Test'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'errors'
            ]);
        Faculty::find(4)->refresh();
        $this->assertDatabaseHas('faculties', [
            'id' => 4,
            'name' => 'Facultad de Test'
        ]);
    }

    /** @test */
    public function register_a_new_faculty_without_name_field()
    {
        $response = $this->postJson('/faculties', [
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonFragment([
                'errors' => 'El campo nombre es obligatorio.'
            ]);
    }

    /** @test */
    public function register_a_new_faculty_with_name_invalidate()
    {
        $response = $this->postJson('/faculties', [
            'name' => 'Faculty'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonFragment([
                'errors' => 'El nombre de la facultads debe exceder los 10 caracteres.'
            ]);
    }

    /** @test */
    public function register_a_new_faculty_with_name_duplicated()
    {
        $response = $this->postJson('/faculties', [
            'name' => 'Facultad de Informática, Electrónica y Comunicación'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonFragment([
                'errors' => 'Registro duplicado'
            ]);
    }

    /** @test */
    public function register_a_new_faculty_with_name_that_contain_invalidate_character()
    {
        $response = $this->postJson('/faculties', [
            'name' => 'Facultad de* 1ng3n13r?'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonFragment([
                'errors' => 'El nombre de la facultad debe contener solo caracteres alfanuméricos.'
            ]);
    }
}