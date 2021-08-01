<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'user_id'=>$this->user_id,
            'onboarding_percentage' => $this->onboarding_percentage,
            'count_applications' => $this->count_applications,
            'count_accepted_applications' => $this->count_accepted_applications
        ];
    }
}
