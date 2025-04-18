<?php

	namespace Tests\Feature;

	use App\Models\Room;
	use App\Models\User;
	use App\Notifications\BookingConfirmation;
	use Carbon\Carbon;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Notification;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class CreateBookingTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		const USER_STORE_BOOKING_ROUTE = 'bookings.store';

		/**
		 * A basic test example.
		 */
		public function test_book_room_successfully(): void
		{
			Notification::fake();

			$user = User::factory()->create();
			$room = Room::factory()->create([
				'is_available' => true,
			]);

			Sanctum::actingAs($user);
			$data = [
				'room_id' => $room->id,
				'check_in' => Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$response = $this->postJson(route(self::USER_STORE_BOOKING_ROUTE), $data);

			$response->assertCreated();
			$response->assertJsonStructure([]);
			$this->assertDatabaseHas('room_user', $data);
			Notification::assertSentTo($user, BookingConfirmation::class);
		}

		/**
		 * A basic test example.
		 */
		public function test_book_room_failed_as_room_not_available_successfully(): void
		{
			Notification::fake();

			$user = User::factory()->create();
			$room = Room::factory()->create([
				'is_available' => false,
			]);

			Sanctum::actingAs($user);
			$data = [
				'room_id' => $room->id,
				'check_in' => Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$response = $this->postJson(route(self::USER_STORE_BOOKING_ROUTE), $data);

			$response->assertBadRequest();
			$this->assertDatabaseMissing('room_user', $data);
			Notification::assertNotSentTo($user, BookingConfirmation::class);
		}

		/**
		 * A basic test example.
		 */
		public function test_book_room_failed_as_room_reserved_before(): void
		{
			Notification::fake();

			$user = User::factory()->create();
			$room = Room::factory()->create([
				'is_available' => true,
			]);



			Sanctum::actingAs($user);
			$data = [
				'room_id' => $room->id,
				'check_in' => Carbon::now()->addDay()->toDateString(),
				'check_out' => Carbon::now()->addDays(3)->toDateString(),
			];

			$data['total_price'] = fake()->randomFloat(2, 10, 1000);
			$user->rooms()->attach($room->id, $data);

			$response = $this->postJson(route(self::USER_STORE_BOOKING_ROUTE), $data);

			$response->assertBadRequest();
			$this->assertDatabaseHas('room_user', $data);
			Notification::assertNotSentTo($user, BookingConfirmation::class);
		}
	}
