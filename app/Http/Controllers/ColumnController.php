<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColumnCollection;
use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
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
	 * Remove the specified resource from storage.
	 *
	 * @param Column $column
	 * @return JsonResponse
	 */
	public function destroy(Column $column)
	{
		$column->cards()->delete();

		$column->delete();

		return response()->json([
			'result' => 'success'
		]);
	}

	private function getColumns()
	{
		$columns = Column::with([
			'cards' => function ($q) {
				$q->orderBy('sort_number');
			}
		])
			->orderBy('id')
			->get();

		return new ColumnCollection($columns);
	}
}
