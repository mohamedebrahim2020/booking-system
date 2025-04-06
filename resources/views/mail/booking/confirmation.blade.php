<x-mail::message>
# Introduction
    hello {{$user_name}}
    you successfully confirmed a booking .
    your booking
    from: {{$check_in}}
    to: {{$check_out}}.
    with total price : {{$total_price}}
    in Room: {{$room_name}} .
    description: {{$room_description}}
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
