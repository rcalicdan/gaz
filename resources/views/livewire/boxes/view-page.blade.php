<div>
    <h1>{{ __('Box') }} #{{ $pickupBox->box_number ?? $pickupBox->id }}</h1>

    <div>
        <p><strong>{{ __('Note') }}:</strong> {{ $pickupBox->note ?? '' }}</p>
        <p><strong>{{ __('Pickup') }}:</strong> <a href="{{ route('pickups.view', $pickupBox->pickup_id) }}">{{ $pickupBox->pickup_id }}</a></p>
        <p><strong>{{ __('Added') }}:</strong> {{ $pickupBox->created_at }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('pickups.view', $pickupBox->pickup_id) }}" class="btn btn-secondary">{{ __('Back to pickup') }}</a>
    </div>
</div>
