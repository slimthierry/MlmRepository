<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientMembershipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return
        [
            'id' => $this->id,
            'ref_id' => $this->ref_id,
            'parrain_id' => $this->parrain_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'code' => $this->code,
            'member_level' => $this->member_level,
            'created_at' => $this->create_at,
            // 'href' => [
            //     'link' => route('member.show',$this->id)
            // ]
        ];
    }
}
