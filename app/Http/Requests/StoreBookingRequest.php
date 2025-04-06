<?php

	namespace App\Http\Requests;

	use Illuminate\Foundation\Http\FormRequest;

	class StoreBookingRequest extends FormRequest
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
				'room_id' => 'required|exists:rooms,id',
				'check_in' => 'required|date|date_format:Y-m-d|after:today',
				'check_out' => 'required|date|date_format:Y-m-d|after:check_in',
			];
		}
	}