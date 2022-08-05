<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id'          => $this->id,
			'title'       => $this->title,
			'created_at'  => $this->created_at ? date('Y-m-d H:i:s', strtotime($this->created_at)) : NULL,
			'description' => $this->description,
			'deleted_at'  => $this->deleted_at ? date('Y-m-d H:i:s', strtotime($this->deleted_at)) : NULL
		];
	}
}
