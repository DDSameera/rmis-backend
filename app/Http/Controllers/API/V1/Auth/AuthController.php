<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\SendResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{


    use SendResponseTrait;

    private $userRepository;

    /**
     * UserRepositoryInterface constructor.
     * @param $userRepository
     */

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

    }

    /**/

    public function register(RegisterRequest $registerRequest)
    {
        //Capture User Inputs
        $formData = (object)$registerRequest->all();

        //Store Validated User Inputs
        $user = $this->userRepository->createUser($formData);

        try {

            if ($user) {
                //Encrypted Token Generate
                $token = Crypt::encrypt($user->createToken('rmisbackend')->plainTextToken);

                //Send Response with Formatted User Data
                $response = [
                    'token' => $token,
                    'user' => new UserResource($user)
                ];

                return SendResponseTrait::sendSuccessWithToken($response, "User Created Successfully", 200);
            }
        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error($modelNotFoundException->getMessage(), $modelNotFoundException->getTrace());
            return SendResponseTrait::sendErrorWithToken('Unable to Save Record. Please Contact System Admin (Error No: 001 )', "Error", 404);

        } catch (Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return SendResponseTrait::sendErrorWithToken('Sorry.Something went wrong. Please Contact System Admin (Error No: 002 )', "Error", 404);

        }

    }

    public function login(LoginRequest $loginRequest)
    {

        //Check Valid User
        $user = User::where('email', $loginRequest->input('email'))->first();

        //Check Valid User & Password
        if (!$user || !Hash::check($loginRequest->input('password'), $user->password)) {
            return SendResponseTrait::sendErrorWithToken('Invalid email or password', "Error", 401);
        }

        //Valid
        $token = Crypt::encrypt($user->createToken('rmisbackend')->plainTextToken);

        //Send Response with Formatted User Data
        $response = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return SendResponseTrait::sendSuccessWithToken($response, "User Logged Successfully", 200);

    }
}
