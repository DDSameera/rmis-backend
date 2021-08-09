<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\PrivilegeTrait;
use App\Traits\SendResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;



class AuthController extends Controller

{

    use SendResponseTrait, PrivilegeTrait;

    private UserRepositoryInterface $userRepository;

    /**
     * UserRepositoryInterface constructor.
     * @param UserRepositoryInterface $userRepository
     */

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

    }

    /**
     * Register New User (Roles : Admin,User)
     *
     * @param RegisterRequest $registerRequest
     * @return JsonResponse
     */

    public function register(RegisterRequest $registerRequest): JsonResponse

    {

        //Capture User Inputs
        $formData = (object)$registerRequest->all();


        //Store Validated User Inputs
        $user = $this->userRepository->createUser($formData);


        //If User Create Unsuccess
        if (!$user) {
            return SendResponseTrait::sendError('Something goes wrong,please try again later', "Error", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //Send Response with Formatted User Data
        $response = [
            'user' => new UserResource($user)
        ];

        return SendResponseTrait::sendSuccessWithToken($response, "User Created Successfully", Response::HTTP_CREATED);

    }

    /**
     * Login User (Roles : Admin,User)
     *
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */

    public function login(LoginRequest $loginRequest): JsonResponse
    {

        //Authentication
        $isAuthenticatedUser = Auth::attempt(['email' => $loginRequest->input('email'), 'password' => $loginRequest->input('password')]);

        if (!$isAuthenticatedUser) {
            return SendResponseTrait::sendError('Invalid Email or Password', "Error", Response::HTTP_UNAUTHORIZED);

        }

        //Check Valid User
        $user = User::where('email', $loginRequest->input('email'))->first();

        //Privileges Setup
        $privileges = $this->getPrivileges($user);


        //Issue New Token based up on device name
        $token = $user->createToken($loginRequest->input('deviceName'), $privileges)->plainTextToken;


        //Send Response with Formatted User Data
        $response = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return SendResponseTrait::sendSuccessWithToken($response, "User Logged Successfully", Response::HTTP_OK);

    }

    /**
     * Logout User (Roles : Admin,User)
     * Delete All User Related Tokens
     *
     *
     * @return JsonResponse
     */

    public function logout(): JsonResponse
    {
        //Get Current Logged User
        $user = Auth::user();

        //Delete All Tokens
        $user->tokens()->delete();


        return SendResponseTrait::sendSuccessWithToken(null, "User Logged Out Successfully", Response::HTTP_OK);
    }
}
