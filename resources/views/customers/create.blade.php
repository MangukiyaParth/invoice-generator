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
                        <input type="name" name="name" id="name" class="form-control" placeholder="Enter Name"
                            required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class = "form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" placeholder="Enter Address" rows="3"></textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control" placeholder="Enter City"
                            required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label">State</label>
                        <input type="text" id="state" name="state" class="form-control" placeholder="Enter State"
                            required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control" placeholder="Enter Country"
                            required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="Enter Zip Code"
                            required>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="gst_number" class="form-label">GSTIN Number</label>
                        <input type="text" id="gst_number" name="gst_number" class="form-control" required>

                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="place_of_supply" class="form-label">Place of supply</label>
                        <input type="text" id="place_of_supply" name="place_of_supply" class="form-control" required>

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
