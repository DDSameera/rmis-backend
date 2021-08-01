<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantRequest;
use App\Http\Resources\ApplicantResource;
use App\Models\Applicant;
use App\Repositories\Applicant\ApplicantRepositoryInterface;
use App\Traits\SendResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class ApplicantController extends Controller
{


    use SendResponseTrait;

    private ApplicantRepositoryInterface $applicantRepository;

    /**
     * ApplicantInterface constructor.
     * @param ApplicantRepositoryInterface $applicantRepository
     */


    public function __construct(ApplicantRepositoryInterface $applicantRepository)
    {
        $this->applicantRepository = $applicantRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {


        $applicants = $this->applicantRepository->getAllWithPagination();

        if (count($applicants) == 0) {
            return SendResponseTrait::sendError('No data found', "Warning", Response::HTTP_NOT_FOUND);
        }


        return response()->json($applicants, Response::HTTP_OK);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicantRequest $applicantRequest
     * @return JsonResponse
     */
    public function store(ApplicantRequest $applicantRequest): JsonResponse
    {

        //Capture User Inputs
        $formData = $applicantRequest->all();

        //Set Current Logged user Id
        $formData['user_id'] = Auth::id();

        //Convert Form Input to Object
        $formData = (object)$formData;


        //Store Validated User Inputs
        $applicant = $this->applicantRepository->create($formData);


        //Send Response with Formatted User Data
        $response = [
            'user' => new ApplicantResource($applicant)
        ];
        return SendResponseTrait::sendSuccessWithToken($response, "Applicant Information has been saved successfully ", Response::HTTP_OK);

    }

    /**
     * Display the specified resource.
     *
     * @param $user_id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {


        //Paginated Data
        $applicant = $this->applicantRepository->find($id);

        //  Send Response with Formatted User Data
        $response = [
            'applicant' => new ApplicantResource($applicant)
        ];

        return SendResponseTrait::sendSuccessWithToken($response, "Applicant Information has been found.", Response::HTTP_OK);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApplicantRequest $applicantRequest
     * @param $user_id
     * @return JsonResponse
     */
    public function update(ApplicantRequest $applicantRequest, $id): JsonResponse
    {

        //Capture User Inputs
        $formData = (object)$applicantRequest->all();


        //Store Validated User Inputs
        $applicant = $this->applicantRepository->update($formData, $id);

        if ($applicant == 0) {
            return SendResponseTrait::sendError('Applicant data already updated.', "Error", Response::HTTP_OK);
        }

        //  Send Response with Formatted User Data
        $response = [
            'updated_data' => new ApplicantResource(Applicant::where('user_id', $id)->first())
        ];

        return SendResponseTrait::sendSuccessWithToken($response, "Applicant Information has been updated successfully ", Response::HTTP_OK);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user_id
     * @return JsonResponse
     */
    public function destroy($user_id): JsonResponse
    {


        $delete = $this->applicantRepository->destroy($user_id);

        if (!$delete) {
            return SendResponseTrait::sendError('Applicant cannot found in database', "Warning", Response::HTTP_NOT_FOUND);
        }


        //  Send Response with Formatted User Data
        $response = [
            'user_id' => $user_id
        ];


        return SendResponseTrait::sendSuccessWithToken($response, "Applicant Information has been deleted successfully ", Response::HTTP_OK);


    }
}
