<?php
	namespace App\Repositories;

	use App\Models\Room;
	use Carbon\Carbon;

	class RoomRepository extends BaseRepository
	{
		/**
		 * UserRepository constructor.
		 *
		 * @param Room $model
		 */
		public function __construct(Room $model)
		{
			parent::__construct($model);
		}

		public function checkAvailability(array $data): bool
		{
			$room = $this->model->find($data['room_id']);
			if (!$room->is_available || $this->checkSameTimeReservation($room, $data))
			{
				return false;
			}
			return true;
		}

		/**
		 * @param Room $room
		 * @param array $data
		 * @return bool
		 */
		private function checkSameTimeReservation(Room $room, array $data) : bool
		{
			return $room->users()->where(function ($query) use ($data) {
				$query->where(function ($q) use ($data) {
					$q->whereDate('check_in', '<=', $data['check_in'])
						->whereDate('check_out', '>=', $data['check_in']);
				})->orWhere(function ($q) use ($data) {
					$q->whereDate('check_in', '<=', $data['check_in'])
						->whereDate('check_out', '>=', $data['check_out']);
				});
			})->exists();
		}

		public function calculateTotalPrice(int $roomId, array $data): float
		{
			$price = $this->find($roomId)->price_per_night;
			$nights = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));
			return $price * $nights;
		}
	}
