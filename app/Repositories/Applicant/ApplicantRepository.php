<?php


namespace App\Repositories\Applicant;


use App\Models\Applicant;



class ApplicantRepository implements ApplicantRepositoryInterface
{

    /**
     * @var Applicant
     */

    protected $applicant;


    /**
     * ApplicantRepository constructor.
     *
     * @param Applicant $applicant
     */

    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * @param Object $applicant
     * @param $user_id
     *
     * @return Object
     */

    public function getAllWithPagination(): object
    {
        return $this->applicant->paginate(3);
    }

    /**
     * @param Object $applicant
     * @param $applicant
     *
     * @return Applicant
     */


    public function create(object $applicant): Applicant
    {

        return $this->applicant->create([
            'user_id' => $applicant->user_id,
            'onboarding_percentage' => $applicant->onboarding_percentage,
            'count_applications' => $applicant->count_applications,
            'count_accepted_applications' => $applicant->count_accepted_applications,

        ]);
    }

    /**
     * @param Object $applicant
     * @param $user_id
     *
     * @return integer
     */


    public function update(object $applicant, $user_id): int
    {

        return $this->applicant->where("user_id", $user_id)->update([
            'onboarding_percentage' => $applicant->onboarding_percentage,
            'count_applications' => $applicant->count_applications,
            'count_accepted_applications' => $applicant->count_accepted_applications,
        ]);

    }

    /**
     *
     * @param $user_id
     *
     * @return Applicant
     */

    public function find($user_id)
    {



       return $this->applicant->where('user_id',"=",$user_id)->firstorFail();
    }


    /**
     *
     * @param $user_id
     *
     * @return bool
     */

    public function destroy($user_id): bool
    {
        return $this->applicant->where('user_id', $user_id)->delete();

    }


}