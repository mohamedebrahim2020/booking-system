<?php

	namespace Tests\Feature;

	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class LogoutTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		/**
		 * A basic test example.
		 */
		public function test_logout_returns_a_successful_response(): void
		{
			$user = User::factory()->create([
				'password' => $password = $this->faker->password(8),
			]);


			Sanctum::actingAs($user);

			$response = $this->postJson(route('logout'));
			$response->assertOk();
			$response->assertJsonStructure([
				'message',
			]);
			$this->assertEquals("Logged out", $response->json('message'));
		}

		/**
		 * A basic test example.
		 */
		public function test_login_returns_a_invalid_data_as_already_logged_response(): void
		{
			$response = $this->postJson(route('logout'));

			$response->assertUnauthorized();
		}
	}
