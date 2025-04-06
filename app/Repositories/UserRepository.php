<?php

	namespace App\Repositories;

	use App\Models\User;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;

	class UserRepository extends BaseRepository
	{
		/**
		 * UserRepository constructor.
		 *
		 * @param User $model
		 */
		public function __construct(User $model)
		{
			parent::__construct($model);
		}

		/**
		 * @param $email
		 * @return Model|null
		 */
		public function findByEmail($email): ?Model
		{
			return $this->model->where('email', $email)->first();
		}

		/**
		 * @param array $data
		 * @return Model|null
		 */
		public function bookARoom(array $data): ?Model
		{
			return auth()->user()->rooms()->attach($data['room_id'], $data);
		}


		public function getBooking($id)
		{
			return DB::table('room_user')->where('id', $id)->first();
		}
	}