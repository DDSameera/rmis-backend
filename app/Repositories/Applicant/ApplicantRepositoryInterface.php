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

    public function update(object $applicant, $id);

    public function find($id);

    public function destroy($id);


}