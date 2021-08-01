<?php

namespace App\Http\Controllers\API\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\Privilege;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Response;


class AuthController extends Controller

{

    use SendResponseTrait, Privilege;

    private $userRepository;

    /**
     * UserRepositoryInterface constructor.
     * @param UserRepositoryInterface $userRepository
     */

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

    }



    public function register(RegisterRequest $registerRequest)
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

    public function login(LoginRequest $loginRequest)
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


        //Delete User's Existing Tokens
        $user->tokens()->delete();

        //Issue New Token
        $token = $user->createToken(Auth::user()->email, $privileges)->plainTextToken;


        //Send Response with Formatted User Data
        $response = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return SendResponseTrait::sendSuccessWithToken($response, "User Logged Successfully", Response::HTTP_OK);

    }

    public function logout()
    {
        //Get Current Logged User
        $user = Auth::user();

        //Delete All Tokens
        $user->tokens()->delete();


        return SendResponseTrait::sendSuccessWithToken(null, "User Logged Out Successfully", Response::HTTP_OK);
    }
}
