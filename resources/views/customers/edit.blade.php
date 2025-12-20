@extends('layouts.main')

@section('page-title', 'Edit Customer')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Customer Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $customer->name) }}" placeholder="Enter Name" >
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="nick_name" class = "form-label">Nick Name</label>
                        <input type="nick_name" name="nick_name" id="nick_name" class="form-control" placeholder="Enter Nick Name" value="{{ $customer->nick_name }}"  >
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="{{ old('email', $customer->email) }}" placeholder="Enter Email" >
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" placeholder="Enter Address" rows="3">{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="{{ old('city', $customer->city) }}" placeholder="Enter City" >
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label">State</label>
                        <input type="text" id="state" name="state" class="form-control"
                               value="{{ old('state', $customer->state) }}" placeholder="Enter State" >
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control"
                               value="{{ old('country', $customer->country) }}" placeholder="Enter Country" >
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control"
                               value="{{ old('zip_code', $customer->zip_code) }}" placeholder="Enter Zip Code" >
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="gst_number" class="form-label">GST Number</label>
                        <input type="text" id="gst_number" name="gst_number" class="form-control" placeholder="Enter GST Number"
                               value="{{ old('gst_number', $customer->gst_number) }}" >
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="place_of_supply" class="form-label">Place of Supply</label>
                        <input type="text" id="place_of_supply" name="place_of_supply" class="form-control" placeholder="Enter Place of supply"
                               value="{{ old('place_of_supply', $customer->place_of_supply) }}" >
                    </div>

                    <div class="modal-footer flex gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill nick name when name is entered
    $('#name').on('input', function() {
        $('#nick_name').val($(this).val());
    });
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
                input.closest('.mb-3').append('<div class="error-message text-danger mt-1">' + message + '</div>');
                isValid = false;
            }
        });
        
        // Email validation
        const email = $('[name="email"]').val().trim();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('[name="email"]').addClass('is-invalid');
            $('[name="email"]').closest('.mb-3').append('<div class="error-message text-danger mt-1">Please enter a valid email</div>');
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
