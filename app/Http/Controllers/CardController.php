<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Traits\ColumnData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
	use ColumnData;

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request)
	{
		$request->validate([
			'title'       => ['required'],
			'description' => ['required']
		]);

		$card = new Card();
		$card->fill($request->all());
		$card->save();

		return response()->json([
			'result'  => 'success',
			'columns' => $this->getColumns()
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param Card $card
	 * @return JsonResponse
	 */
	public function update(Request $request, Card $card)
	{
		$request->validate([
			'title'       => ['required'],
			'description' => ['required']
		]);

		$card->fill($request->all());
		$card->save();

		return response()->json([
			'result' => 'success'
		]);
	}
}
