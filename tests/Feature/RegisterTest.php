<?php

	namespace Tests\Feature;

	use App\Models\User;
	use Illuminate\Auth\Notifications\VerifyEmail;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Notification;
	use Tests\TestCase;

	class RegisterTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		/**
		 * A basic test example.
		 */
		public function test_register_returns_a_successful_response(): void
		{
			Notification::fake();

			$data = [
				'email' => $this->faker->email,
				'name' => $this->faker->name,
				'password' => '123456789',
				'password_confirmation' => '123456789',
				'role' => 'admin'
			];
			$response = $this->postJson(route('register'), $data);
			$response->assertCreated();
			$response->assertJsonStructure([
				'message',
				'token',
			]);
			$this->assertDatabaseHas('users', ['email' => $data['email']]);

			// Assert the email verification notification was sent
			Notification::assertSentTo(
				[User::first()],
				VerifyEmail::class
			);
		}

		/**
		 * A basic test example.
		 */
		public function test_register_returns_a_invalid_data_response(): void
		{
			Notification::fake();

			$data = [
				'email' => $this->faker->email,
				'name' => $this->faker->name,
				'password' => '123456789',
				'role' => 'admins'
			];
			$response = $this->postJson(route('register'), $data);

			$response->assertUnprocessable();
			$response->assertInvalid(['password', 'role']);
			$this->assertDatabaseMissing('users', ['email' => $data['email']]);
		}
	}
