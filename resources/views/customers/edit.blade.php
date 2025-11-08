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
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name', $customer->name) }}" placeholder="Enter Name" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" 
                               value="{{ old('email', $customer->email) }}" placeholder="Enter Email" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" placeholder="Enter Address" rows="3">{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="{{ old('city', $customer->city) }}" placeholder="Enter City" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label">State</label>
                        <input type="text" id="state" name="state" class="form-control"
                               value="{{ old('state', $customer->state) }}" placeholder="Enter State" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control"
                               value="{{ old('country', $customer->country) }}" placeholder="Enter Country" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control"
                               value="{{ old('zip_code', $customer->zip_code) }}" placeholder="Enter Zip Code" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="gst_number" class="form-label">GSTIN Number</label>
                        <input type="text" id="gst_number" name="gst_number" class="form-control"
                               value="{{ old('gst_number', $customer->gst_number) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="place_of_supply" class="form-label">Place of Supply</label>
                        <input type="text" id="place_of_supply" name="place_of_supply" class="form-control"
                               value="{{ old('place_of_supply', $customer->place_of_supply) }}" required>
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
