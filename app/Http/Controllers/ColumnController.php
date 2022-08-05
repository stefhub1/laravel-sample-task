<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Traits\ColumnData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
	use ColumnData;

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
	 */
	public function index()
	{
		return response()->json([
			'result' => $this->getColumns()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request)
	{
		$request->validate([
			'title' => 'required'
		]);

		$column = new Column();
		$column->fill($request->all());
		$column->save();

		return response()->json([
			'result'  => 'success',
			'columns' => $this->getColumns()
		]);
	}

	/**
	 * Update card sort numbers
	 *
	 * @param Request $request
	 * @param Column $column
	 * @return JsonResponse
	 */
	public function update(Request $request, Column $column)
	{
		$cards = $request->input('cards');
		if ($cards) {
			foreach ($cards as $card) {
				$columnCard = $column->cards()->find($card['id']);
				$columnCard->sort_number = $card['sort_number'];
				$columnCard->save();
			}
		}

		return response()->json([
			'result' => 'success'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Column $column
	 * @return JsonResponse
	 */
	public function destroy(Column $column)
	{
		$column->cards()->update([
			'deleted_at' => now()
		]);

		$column->deleted_at = now();
		$column->save();

		return response()->json([
			'result' => 'success'
		]);
	}
}
