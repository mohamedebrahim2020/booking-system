<?php

	namespace App\Http\Controllers;

	use App\Http\Requests\StoreRoomRequest;
	use App\Http\Requests\UpdateRoomRequest;
	use App\Http\Resources\RoomCollection;
	use App\Services\RoomService;
	use Illuminate\Http\Response;

	class RoomController extends Controller
	{
		protected RoomService $roomService;

		public function __construct(RoomService $roomService)
		{
			$this->roomService = $roomService;
		}

		public function index()
		{
			return response()->json(new RoomCollection($this->roomService->index()),Response::HTTP_OK);
		}

		public function store(StoreRoomRequest $request)
		{
			$this->roomService->store($request->validated());
			return response()->json(['message' => 'Room created successfully!'],Response::HTTP_CREATED);
		}

		public function update(UpdateRoomRequest $request, $id)
		{
			$this->roomService->update($request->validated(), $id);
			return response()->json(['message' => 'Room updated successfully!'],Response::HTTP_OK);
		}

		public function destroy($id)
		{
			$this->roomService->delete($this->roomService->show($id));
			return response()->json(['message' => 'Room deleted successfully!'],Response::HTTP_OK);
		}
	}