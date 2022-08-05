<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiController extends Controller
{
	/**
	 * Get card list
	 *
	 * @param Request $request
	 *
	 * @return AnonymousResourceCollection
	 */
	public function getCards(Request $request)
	: AnonymousResourceCollection
	{
		$card = Card::all();

		if ($request->has('date')) {
			$card->where('date(created_at)', $request->input('date'));
		}

		if ($request->has('status')) {
			$card->where('status', $request->input('status'));
		}

		return CardResource::collection($card);
	}
}
