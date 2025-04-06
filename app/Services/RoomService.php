<?php

	namespace App\Services;

	use App\Repositories\RoomRepository;

	class RoomService extends BaseService
	{
		public function __construct(RoomRepository $repository)
		{
			$this->repository = $repository;
		}

		public function getBooking($id)
		{
			return $this->repository->getBooking($id);
		}
	}
