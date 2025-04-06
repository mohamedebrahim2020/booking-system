<?php

	namespace Tests\Feature;

	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class LoginTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		/**
		 * A basic test example.
		 */
		public function test_login_returns_a_successful_response(): void
		{
			$user = User::factory()->create([
				'password' => $password = $this->faker->password(8),
			]);

			$data = [
				'email' => $user->email,
				'password' => $password,
			];
			Sanctum::actingAs($user);

			$response = $this->postJson(route('login'), $data);
			$response->assertOk();
			$response->assertJsonStructure([
				'token',
			]);
			$this->assertDatabaseHas('users', ['email' => $data['email']]);
		}

		/**
		 * A basic test example.
		 */
		public function test_login_returns_a_invalid_data_response(): void
		{
			$user = User::factory()->create([
				'password' => $password = $this->faker->password(8),
			]);

			$data = [
				'email' => $user->email . 'jdjdj',
				'password' => $password,
			];
			Sanctum::actingAs($user);

			$response = $this->postJson(route('login'), $data);

			$response->assertUnprocessable();
			$response->assertInvalid(['email']);
			$this->assertDatabaseMissing('users', ['email' => $data['email']]);
		}

		/**
		 * A basic test example.
		 */
		public function test_login_returns_an_unverified_user_response(): void
		{
			$user = User::factory()->create([
				'email_verified_at' => null,
				'password' => $password = $this->faker->password(8),
			]);

			$data = [
				'email' => $user->email . 'jdjdj',
				'password' => $password,
			];
			Sanctum::actingAs($user);

			$response = $this->postJson(route('login'), $data);
			$response->assertForbidden();
		}
	}
