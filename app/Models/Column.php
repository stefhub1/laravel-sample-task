<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Column extends Model
{
	use HasFactory;

	protected $fillable = ['column_title'];

	/**
	 * Card Model relationship
	 *
	 * @return HasMany
	 */
	public function cards()
	: HasMany
	{
		return $this->hasMany(Card::class, 'column_id');
	}
}
