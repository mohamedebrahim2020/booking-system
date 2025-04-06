<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
		'role',
		'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

	/**
	 * Get the user's first name.
	 *
	 * @return \Illuminate\Database\Eloquent\Casts\Attribute
	 */
	protected function role(): Attribute
	{
		$roles = ['user', 'admin'];
		return Attribute::make(
			get: fn (int $value) => $roles[$value],
			set: fn (string $value) => ! ($value == 'user'),
		);
	}

	public function rooms()
	{
		return $this->belongsToMany(Room::class)
			->withPivot(['id', 'check_in', 'check_out', 'total_price'])
			->withTimestamps();
	}
}
