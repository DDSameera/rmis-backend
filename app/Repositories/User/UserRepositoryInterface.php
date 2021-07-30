<?php

namespace App\Repositories\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repositories
 */

interface UserRepositoryInterface
{

    /**
     * @param array $data
     * @return UserModel
     */
    public function createUser(Object $data);
}