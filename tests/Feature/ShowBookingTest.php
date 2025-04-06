<?php

	namespace Tests\Feature;

	use App\Models\Room;
	use App\Models\User;
	use Carbon\Carbon;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Notification;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class ShowBookingTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		const USER_SHOW_BOOKING_ROUTE = 'bookings.show';

		/**
		 * A basic test example.
		 */
		public function test_show_booking_successfully(): void
		{
			$user = User::factory()->create(['role' => 'user']);
			$room = Room::factory()->create([
				'is_available' => true,
			]);

			Sanctum::actingAs($user);
			$data = [
				'id' => 1,
				'room_id' => $room->id,
				'check_in' => Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$data['total_price'] = fake()->randomFloat(2, 10, 1000);
			$user->rooms()->attach($room->id, $data);

			$response = $this->getJson(route(self::USER_SHOW_BOOKING_ROUTE, ['booking' => 1]), $data);

			$response->assertok();
			$this->assertDatabaseHas('room_user', $data);
		}

		/**
		 * A basic test example.
		 */
		public function test_show_booking_room_succes_for_admin(): void
		{
			Notification::fake();

			$user = User::factory()->create();
			$room = Room::factory()->create([
				'is_available' => false,
			]);

			Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
			$data = [
				'id' => 1,
				'room_id' => $room->id,
				'check_in' => Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$data['total_price'] = fake()->randomFloat(2, 10, 1000);
			$user->rooms()->attach($room->id, $data);

			$response = $this->getJson(route(self::USER_SHOW_BOOKING_ROUTE, ['booking' => 1]), $data);

			$response->assertOk();
			$this->assertDatabaseHas('room_user', $data);
		}

		/**
		 * A basic test example.
		 */
		public function test_show_booking_failed_as_user_not_admin_nor_owner(): void
		{
			$user = User::factory()->create();
			$room = Room::factory()->create([
				'is_available' => true,
			]);



			Sanctum::actingAs(User::factory()->create(['role' => 'user']));
			$data = [
				'id' => 1,
				'room_id' => $room->id,
				'check_in' => Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$data['total_price'] = fake()->randomFloat(2, 10, 1000);
			$user->rooms()->attach($room->id, $data);

			$response = $this->getJson(route(self::USER_SHOW_BOOKING_ROUTE, ['booking' => 1]), $data);

			$response->assertUnauthorized();
		}
	}
