<?php

namespace App\Traits;

use App\Http\Resources\ColumnCollection;
use App\Models\Column;

trait ColumnData
{
	/**
	 * Get column data
	 * @return ColumnCollection
	 */
	public function getColumns()
	: ColumnCollection
	{
		$columns = Column::with([
			'cards' => function ($q) {
				$q->whereNull('deleted_at')
					->orWhere('deleted_at', '0000-00-00 00:00:00')
					->orderBy('sort_number');
			}
		])
			->whereNull('deleted_at')
			->orWhere('deleted_at', '0000-00-00 00:00:00')
			->orderBy('id')
			->get();

		return new ColumnCollection($columns);
	}
}