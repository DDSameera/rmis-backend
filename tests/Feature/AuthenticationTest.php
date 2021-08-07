<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Setup
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
        $this->withoutExceptionHandling();
    }

    /**
     * Test Required Fields for Regisreration
     * @return void
     */

    public function test_required_fields_for_registeration(): void
    {
        $this->json('POST', 'api/v1/user/register')
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

    /**
     * Test Success Registeration
     * @return void
     */

    public function test_successful_registeration(): void
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

        $this->json('POST', 'api/v1/user/register', $userData, ['Accept' => 'application/json'])
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

    /**
     * Test Password Confirmation
     * @return void
     */
    public function test_password_confirmation(): void
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

        $this->json('POST', 'api/v1/user/register', $userData, ['Accept' => 'application/json'])
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
