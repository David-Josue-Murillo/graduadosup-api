<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzMxODE4NzE4LCJleHAiOjE3MzE4MjIzMTgsIm5iZiI6MTczMTgxODcxOCwianRpIjoiR01jR0RsTUhVVlhrUG5rRCIsInN1YiI6IjExIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.VUdaXYiNFQzZsczM1NPQzoLydoZrrHOieI5_zwQRICI';
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
