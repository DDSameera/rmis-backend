<?php

namespace App\Traits;


trait Privilege
{

    protected array $privileges;


    public function getPrivileges($user): array
    {

        if ($user->role === "admin") {
            $this->privileges = [
                'applicant-index',
                'applicant-store',
                'applicant-update',
                'applicant-show',
                'applicant-destroy',
            ];
        } else if ($user->role === "user") {
            $this->privileges = [
                ''
            ];
        }

        return $this->privileges;
    }


}