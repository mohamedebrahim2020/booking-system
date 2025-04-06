<?php

namespace App\Notifications;

use App\Repositories\UserRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

	public array $bookingData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $bookingData)
    {
        $this->bookingData = $bookingData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
		$booking = $this->bookingData['user']->rooms()
			->withPivot(['total_price'])
			->where('rooms.id', $this->bookingData['room_id'])
			->where('check_in', $this->bookingData['check_in'])
			->where('check_out', $this->bookingData['check_out'])
			->first();

        return (new MailMessage)->markdown('mail.booking.confirmation',
			[
				'user_name' => $this->bookingData['user']['name'],
				'check_in' => $this->bookingData['check_in'],
				'check_out' => $this->bookingData['check_out'],
				'total_price' => $booking->pivot->total_price,
				'room_name' => $booking->name,
				'room_description' => $booking->description,
			]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
