<?php

namespace Tests\Feature;

use Tests\TestCase;

class NumGraduateTest extends TestCase
{
    protected $headers;
    protected $token;
    protected $jsonResponseEstructure;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzMxODE4NzE4LCJleHAiOjE3MzE4MjIzMTgsIm5iZiI6MTczMTgxODcxOCwianRpIjoiR01jR0RsTUhVVlhrUG5rRCIsInN1YiI6IjExIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.VUdaXYiNFQzZsczM1NPQzoLydoZrrHOieI5_zwQRICI';
        $this->headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ];
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
    }

    /** @test */
    public function regiter_a_new_number_of_graduates(): void
    {
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 9,
            'career_id' => 5
        ];

        $response = $this->withHeaders($this->headers)->postJson('/graduates', $data);
        
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
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 9,
            'career_id' => 5
        ];

        $response = $this->withHeaders($this->headers)->postJson('/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Registro duplicado'
        ]);
    }





















    /** @test */
    public function a_user_requests_to_see_the_entire_number_of_graduates(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $this->token
        ])->get('/graduates');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
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
            ],
            'status'
        ]);
    }
}
