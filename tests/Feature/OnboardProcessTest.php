<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OnboardProcessTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {

        parent::setUp();

    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_excel_file_validation()
    {
        //Logged as Auth User
        $user = User::factory()->create();
        $this->actingAs($user);

        $userData = [
            'excel_file' => null,
        ];

        $this->json('POST', 'api/v1/generate/chart', $userData, [
            'Accept' => 'application/json',
            'Content-type' => 'application/octet-stream'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "message" => "Validation Errors",
                "errors" => [
                    "The excel file field is required."
                ],

            ]);


    }


    public function test_excel_file_type_validation()
    {
        //Logged as Auth User
        $user = User::factory()->create();
        $this->actingAs($user);

        $userData = [
            'excel_file' => UploadedFile::fake()->create('export.pdf')->size(200)->mimeType('text/pdf'),
        ];

        $this->json('POST', 'api/v1/generate/chart', $userData, [
            'Accept' => 'application/json',
            'Content-type' => 'application/octet-stream'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                "success" => false,
                "message" => "Validation Errors",
                "errors" => [
                    "Invalid File Format. Allow Only .csv format"
                ],

            ]);


    }

    public function test_json_chart_data()
    {
        //Logged as Auth User
        $user = User::factory()->create();
        $this->actingAs($user);

        $userData = [
            'excel_file' => UploadedFile::fake()->create('export.csv')->size(200)->mimeType('text/csv'),
        ];

        $this->json('POST', 'api/v1/generate/chart', $userData, [
            'Accept' => 'application/json',
            'Content-type' => 'application/octet-stream'
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "success",
                "message",
            ]);
    }
}
