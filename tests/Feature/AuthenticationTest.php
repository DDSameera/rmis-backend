<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
        $this->withoutExceptionHandling();
    }

    public function test_required_fields_for_registeration()
    {
        $this->json('POST', 'http://127.0.0.1:8000/api/v1/user/register')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                'errors' => [
                    'The fname field is required.',
                    'The mname field is required.',
                    'The lname field is required.',
                    'The email field is required.',
                    'The mobile field is required.',
                    'The password field is required.',
                    'The role field is required.'
                ],
                'message' => "Validation Errors",


            ]);
    }

    public function test_successful_registeration()
    {

        $userData = [
            'fname' => 'Sameera',
            'mname' => 'Dananjaya',
            'lname' => 'Wijerathna',
            'email' => 'digix.samesssesra@gmail.com',
            'mobile' => '0718761292',
            'password' => 'Majority300!',
            'password_confirmation' => 'Majority300!',
            'role' => 'admin'
        ];

        $this->json('POST', 'http://127.0.0.1:8000/api/v1/user/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    "success",
                    "message",
                    "data" => [
                        "user"
                    ]
                ]
            );


    }


    public function test_password_confirmation()
    {
        $userData = [
            'fname' => 'Sameera',
            'mname' => 'Dananjaya',
            'lname' => 'Wijerathna',
            'email' => 'digix.samesssesra@gmail.com',
            'mobile' => '0718761292',
            'password' => 'Majority300!',
            'password_confirmation' => 'Majority300!!!!!',
            'role' => 'admin'
        ];

        $this->json('POST', 'http://127.0.0.1:8000/api/v1/user/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                [
                    "success" => false,
                    'errors' => [
                        'The password confirmation does not match.',
                    ],
                    'message' => "Validation Errors",


                ]
            );

    }





}
