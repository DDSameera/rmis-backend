<?php


namespace App\Repositories\User;


use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserRepository implements UserRepositoryInterface
{

    /**
     * @var UserModel
     */
    protected $user;


    /**
     * UserRepository constructor.
     *
     * @param UserModel
     */

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param array $userAttributes
     *
     * @return UserModel
     */

    public function createUser(Object $data): User
    {

        return $this->user->create([
            'fname' => $data->fname,
            'lname' => $data->lname,
            'mname' => $data->mname,
            'email' => $data->email,
            'mobile' => $data->mobile,
            'password' => Hash::make($data->password)
        ]);


    }

}