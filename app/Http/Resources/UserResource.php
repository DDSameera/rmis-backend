<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'full_name' => ucfirst($this->fname) . " " . $this->mname . " " . $this->lname,
            'email' => $this->email,
            'mobile' => $this->mobile,


        ];
    }

}
