<?php

	namespace App\Http\Requests;

	use Illuminate\Foundation\Http\FormRequest;

	class UpdateRoomRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 */
		public function authorize(): bool
		{
			return true;
		}

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
		 */
		public function rules(): array
		{
			return [
				'name' => 'required|string|max:50',
				'description' => 'required|string|max:255',
				'price_per_night' => 'required|decimal:2',
				'is_available' => 'nullable|boolean',
			];
		}
	}