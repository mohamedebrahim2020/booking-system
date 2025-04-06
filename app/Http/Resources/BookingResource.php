<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'room_id' => $this->room_id,
			'check_in' => $this->check_in,
			'check_out' => $this->check_out,
			'total_price' => $this->total_price,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
