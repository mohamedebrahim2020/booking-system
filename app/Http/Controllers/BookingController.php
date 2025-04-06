<?php

	namespace App\Http\Controllers;

	use App\Http\Requests\ShowBookingRequest;
	use App\Http\Requests\StoreBookingRequest;
	use App\Http\Resources\BookingResource;
	use App\Notifications\BookingConfirmation;
	use App\Services\RoomService;
	use App\Services\UserService;
	use Illuminate\Http\Response;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Gate;

	class BookingController extends Controller
	{
		protected UserService $userService;
		protected RoomService $roomService;

		public function __construct(UserService $userService, RoomService $roomService)
		{
			$this->userService = $userService;
			$this->roomService = $roomService;
		}

		public function store(StoreBookingRequest $request)
		{
			DB::beginTransaction();
			try {
				$this->userService->book($request->validated());
				auth()->user()->notify((new BookingConfirmation($request->safe()->merge(['user' => auth()->user()])->all()))->afterCommit());
				DB::commit();
			} catch (\Exception $e) {
				dd($e);
				DB::rollBack();
				return response()->json(['message' => $e->getMessage()],Response::HTTP_BAD_REQUEST);
			}
			return response()->json(['message' => 'Booking created successfully!'],Response::HTTP_CREATED);
		}

		public function show(ShowBookingRequest $request)
		{
			$booking = $this->userService->getBooking($request->id);
			if (! Gate::allows('show-booking', $booking?->user_id)) {
				return response()->json(['message' => 'you are not the owner nor admin to see this booking']
					, Response::HTTP_UNAUTHORIZED);
			}
			return response()->json(new BookingResource($booking), Response::HTTP_OK);
		}
	}