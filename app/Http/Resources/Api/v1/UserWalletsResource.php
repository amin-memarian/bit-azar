<?php

namespace App\Http\Resources\Api\v1;;

use Illuminate\Http\Resources\Json\JsonResource;

class UserWalletsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'exchange_id' => $this->exchange_id,
            'amount' => $this->amount,
            'wallet' => $this->wallet,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
