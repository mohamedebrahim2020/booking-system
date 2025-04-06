<?php

	namespace Tests\Feature;

	use App\Models\Room;
	use App\Models\User;
	use Carbon\Carbon;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Queue;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class CreateBookingTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		const USER_STORE_BOOKING_ROUTE = 'bookings.store';
		/**
		 * A basic test example.
		 */
		public function test_send_message_successfully(): void
		{
			$this->withoutExceptionHandling();
			Queue::fake();
			$user = User::factory()->create();
			$room = Room::factory()->create();

			Sanctum::actingAs($user);
			$data = [
				'room_id' => $room->id,
				'check_in' => $ss = Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$response = $this->postJson(route(self::USER_STORE_BOOKING_ROUTE), $data);

			$response->assertCreated();
			$response->assertJsonStructure([]);
			$this->assertDatabaseHas('room_user', $data);
		}
	}
