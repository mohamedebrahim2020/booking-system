<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	use HasFactory;

	/**
	 * The attributes that are default valued.
	 *
	 * @var list<string>
	 */
	protected $attributes = [
		'is_available' => true,
	];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'description',
		'price_per_night',
		'is_available',
	];

	public function users()
	{
		return $this->belongsToMany(User::class)
					->withPivot(['id', 'check_in', 'check_out'])
			        ->withTimestamps();
	}
}
