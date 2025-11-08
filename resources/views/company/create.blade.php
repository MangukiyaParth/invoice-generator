@extends('layouts.main')
@section('page-title', 'Company')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Company Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                   <label for="logo" class="form-label">Logo</label>
                    <div class="file-input-wrapper">
                            <input type="file" name="logo" id="logo" class="file-input" accept="image/*">
                            <label for="logo" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                                <span class="file-input-text">Choose logo file or drag and drop</span>
                            </label>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="currency" class="form-label">Currency</label>
                     <select class="form-select form-control" name="currency" id="currencySelect" required>
                            <option value="INR">₹ Rupees (INR)</option>
                            <option value="USD">$ Dollar (USD)</option>
                    </select>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="name" class = "form-label">Name</label>
                    <input type="name" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class = "form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                </div>
                 <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-control" placeholder="Enter Address" rows="3"></textarea>
                </div>
               <div class="mb-3 col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="Enter City"  required>
                </div>
               <div class="mb-3 col-md-6">
                    <label for="state" class="form-label">State</label>
                    <input type="text" id="state" name="state" class="form-control" placeholder="Enter State" required>
                </div>
                 <div class="mb-3 col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" id="country" name="country" class="form-control" placeholder="Enter Country" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="zip_code" class="form-label">Zip Code</label>
                    <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="Enter Zip Code" required>
                </div>
                 <div class="mb-3 col-md-6">
                        <label for="gst_number" class="form-label">GSTIN Number</label>
                        <input type="text" id="gst_number" name="gst_number" class="form-control" placeholder="Enter GSTIN Number" required>
                        @error('gst_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                </div>
                <div class="mb-3 col-md-6">
                        <label for="lut_number" class="form-label">LUT Number</label>
                        <input type="text" id="lut_number" name="lut_number" class="form-control" placeholder="Enter LUT Number" required>
                </div>
                 <div class="mb-3 col-md-6">
                        <label for="euid_number" class="form-label">EUID Number</label>
                        <input type="text" id="euid_number" name="euid_number" class="form-control" placeholder="Enter EUID Number"  required>
                </div>
                <div class="mb-3 col-md-6">
                        <label for="notes" class="form-label">Notes</label>
                        <input type="text" id="notes" name="notes" class="form-control" placeholder="Enter Notes" required>
                </div>
                <div class="mb-3">
                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                    <textarea id="terms_conditions" name="terms_conditions" class="form-control" placeholder="Enter Terms & Conditions" rows="3"></textarea>
                </div>
                <div class="modal-footer flex gap-2">
                    <a href="{{ route('company.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    <button class="btn btn-primary" type="submit">{{__('Create')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection