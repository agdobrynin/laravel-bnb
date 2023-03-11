Hello {{ $booking->personAddress->first_name }}!

Your booking "{{ $booking->bookable->title }}" from {{ $booking->start }} to {{ $booking->end }}.

Please leave feedback with rating at:
{{ env('APP_URL') }}/review/{{ $booking->review_key }}

--
Best regards {{ env('APP_NAME') }}
