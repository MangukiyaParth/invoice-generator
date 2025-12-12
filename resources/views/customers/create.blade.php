@extends('layouts.main')

@section('page-title', 'Customer')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Customer Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="name" name="name" id="name" class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class = "form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" placeholder="Enter Address" rows="3"></textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control" placeholder="Enter City">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label">State</label>
                        <input type="text" id="state" name="state" class="form-control" placeholder="Enter State">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control"
                            placeholder="Enter Country">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control"
                            placeholder="Enter Zip Code">
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="gst_number" class="form-label">GSTIN Number</label>
                        <input type="text" id="gst_number" name="gst_number" class="form-control">

                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="place_of_supply" class="form-label">Place of supply</label>
                        <input type="text" id="place_of_supply" name="place_of_supply" class="form-control">

                    </div>

                    <div class="modal-footer flex gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        <button class="btn btn-primary" type="submit">{{ __('Create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                let isValid = true;

                // Clear previous errors
                $('.error-message').remove();
                $('.is-invalid').removeClass('is-invalid');

                // Required fields validation
                const requiredFields = {
                    'name': 'Name is required',
                    'email': 'Email is required'
                };

                $.each(requiredFields, function(field, message) {
                    const input = $('[name="' + field + '"]');
                    const value = input.val() ? input.val().trim() : '';

                    if (value === '') {
                        input.addClass('is-invalid');
                        input.closest('.mb-3').append(
                            '<div class="error-message text-danger mt-1">' + message + '</div>');
                        isValid = false;
                    }
                });

                // Email validation
                const email = $('[name="email"]').val().trim();
                if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $('[name="email"]').addClass('is-invalid');
                    $('[name="email"]').closest('.mb-3').append(
                        '<div class="error-message text-danger mt-1">Please enter a valid email</div>');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
