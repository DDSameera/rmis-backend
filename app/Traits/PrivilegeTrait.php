<?php

namespace App\Traits;


trait PrivilegeTrait
{

    protected array $privileges;


    public function getPrivileges($user): array
    {

        if ($user->role === "admin") {
            $this->privileges = [
                'generate-chart',
            ];
        } else if ($user->role === "user") {
            $this->privileges = [
                ''
            ];
        }

        return $this->privileges;
    }


}