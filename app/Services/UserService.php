<?php

	namespace App\Services;

	use App\Repositories\RoomRepository;
	use App\Repositories\UserRepository;
	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Response;

	class UserService extends BaseService
	{
		public function __construct(UserRepository $repository)
		{
			$this->repository = $repository;
		}

		public function findByEmail($email): ?Model
		{
			return $this->repository->findByEmail($email);
		}

		public function book(array $data)
		{
			if (app(RoomRepository::class)->checkAvailability($data))
			{
				$data['total_price'] = app(RoomRepository::class)->calculateTotalPrice($data['room_id'], $data);
				$this->repository->bookARoom($data);
				return;
			}

			abort(Response::HTTP_BAD_REQUEST, 'Room is not available for booking.');
		}

		public function getBooking($id)
		{
			return $this->repository->getBooking($id);
		}
	}