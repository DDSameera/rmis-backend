<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\OnboardProcess\OnboardProcessRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;


class OnboardProcessRepositoryTest extends TestCase
{

    private OnboardProcessRepository $onboardProcessRepository;

    private string $sampleFileURI = "";
    private string $sourceFileURI = "";

    const SOURCE_FILE_PATH = "app/excel/export.csv";
    const SAMPLE_FILE_PATH = "app/test/export.csv";

    use RefreshDatabase;


    /**
     * A basic unit test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->onboardProcessRepository = $this->app->make(OnboardProcessRepository::class);
        $this->withoutExceptionHandling();
        $this->withoutMiddleware();
        $this->prepareTestExportFile();


    }


    public function prepareTestExportFile()
    {
        //Delete Export CSV File
        Storage::disk('local')->delete($this->sourceFileURI);

        //Copy Test File to Destination
        $this->sampleFileURI = storage_path(self::SAMPLE_FILE_PATH);
        $this->sourceFileURI = storage_path(self::SOURCE_FILE_PATH);
        if (!file_exists(self::SOURCE_FILE_PATH)) {
            copy($this->sampleFileURI, $this->sourceFileURI);
        }

    }

    public function test_onboard_process_store_sheet_function()
    {
        //Delete Current Export CSV File
        Storage::disk('local')->delete($this->sampleFileURI);


        //Auth User
        $user = User::factory()->create();
        $this->actingAs($user);


        //Make a file with file
        $excelSheet = File::create('excel/export.csv', 100);

        //Pass Excel Sheet to Store Sheet Func
        $this->onboardProcessRepository->storeSheet($excelSheet);


        //Check Report was created & file was stored
        $this->assertEquals('excel/export.csv', 'excel/' . $excelSheet->getClientOriginalName());

        //Check File Exist or Not
        Storage::disk('local')->assertExists('excel/' . $excelSheet->getClientOriginalName());

    }

    public function test_get_formatted_excel_data_function()
    {


        $excelSheetTestData = Excel::toArray([], $this->sourceFileURI)[0];
        $formattedExcelData = $this->onboardProcessRepository->getFormattedExcelData($excelSheetTestData);


        $this->assertArrayHasKey('id', $formattedExcelData[1]);
        $this->assertArrayHasKey('createdAt', $formattedExcelData[1]);
        $this->assertArrayHasKey('percentage', $formattedExcelData[1]);


    }

    public function test_find_steps_percentage_function()
    {


        //Convert Excel Data to Array
        $excelSheetTestData = Excel::toArray([], $this->sourceFileURI)[0];

        //Formatted Imported Excel Array Data
        $excelSheetFormattedTestData = $this->onboardProcessRepository->getFormattedExcelData($excelSheetTestData);

        $actualResult = $this->onboardProcessRepository->findStepsPercentage($excelSheetFormattedTestData)[1];
        $expectedResult = [100, 0, 33, 0, 0, 0, 0, 0];

        $this->assertEquals($expectedResult, $actualResult);
    }


    public function test_divide_by_week_function()
    {
        $excelSheetTestData = Excel::toArray([], $this->sourceFileURI)[0];
        $excelSheetFormattedTestData = $this->onboardProcessRepository->getFormattedExcelData($excelSheetTestData);
        $result = $this->onboardProcessRepository->divideByWeek($excelSheetFormattedTestData)[0];

        $this->assertIsArray($result);
        $this->assertEquals(40,$result[0]);
    }

    public function test_get_chart_code_function()
    {

        $excelSheetTestData = Excel::toArray([], $this->sourceFileURI)[0];

        $excelSheetFormattedTestData = $this->onboardProcessRepository->getFormattedExcelData($excelSheetTestData);

        $result = $this->onboardProcessRepository->getChartCodes($excelSheetFormattedTestData)[1];


        $expectedResult = [
            "name" => "2 weeks later",
            "data" => [
                100,
                0,
                61,
                2,
                0,
                0,
                14,
                24
            ]
        ];

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($expectedResult, $result);
    }


}
