<?php

namespace App\Repositories\Applicant;

use App\Models\Applicant;

interface ApplicantRepositoryInterface
{
    /**
     * @param Object $applicant ,$user_id
     *
     * @return Applicant
     */

    public function getAllWithPagination();

    public function create(object $applicant);

    public function update(object $applicant, $user_id);

    public function find($user_id);

    public function destroy($user_id);


}