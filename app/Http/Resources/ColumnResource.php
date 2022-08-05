<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ColumnResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 * @return array|Arrayable|JsonSerializable
	 */
	public function toArray($request)
	: array|JsonSerializable|Arrayable
	{
		return [
			'id'    => $this->id,
			'title' => $this->title,
			'cards' => $this->cards->map(function ($card) {
				return [
					'id'          => $card->id,
					'column_id'   => $card->column_id,
					'title'       => $card->title,
					'description' => $card->description,
					'sort_number' => $card->sort_number
				];
			})
		];
	}
}
