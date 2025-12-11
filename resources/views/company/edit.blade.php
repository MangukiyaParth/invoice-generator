@extends('layouts.main')
@section('page-title', 'Company')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Company Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('company.update', $company->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="mb-3 col-md-6">
                   <label for="logo" class="form-label">Logo</label>
                     <div class="file-input-wrapper">
                        <input type="file" name="logo" id="logo" class="file-input" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                        <label for="logo" class="file-input-label {{ $company->logo ? 'has-file' : '' }}">
                            <img id="logo-preview" class="file-preview" src="{{ $company->logo ? asset('storage/' . $company->logo) : '' }}" alt="Icon preview" style="{{ $company->icon ? 'display: block;' : 'display: none;' }}">
                            <i class="fas fa-cloud-upload-alt file-input-icon"></i>
                            <span class="file-input-text">Choose icon file or drag and drop</span>
                        </label>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="currency" class="form-label">Currency</label>
                     <select class="form-select form-control" name="currency" id="currencySelect">
                            <option value="INR" {{ $company->currency == 'INR' ? 'selected' : '' }}>₹ Rupees (INR)</option>
                            <option value="USD" {{ $company->currency == 'USD' ? 'selected' : '' }}>$ Dollar (USD)</option>
                    </select>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="name" class = "form-label">Name</label>
                    <input type="name" name="name" id="name" class="form-control" placeholder="Enter Name" value="{{ $company->name }}" >
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class = "form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="{{ $company->email }}" >
                </div>
                 <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-control" placeholder="Enter Address" rows="3">{{ $company->address }}</textarea>
                </div>
               <div class="mb-3 col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="Enter City" value="{{ $company->city }}" >
                </div>
               <div class="mb-3 col-md-6">
                    <label for="state" class="form-label">State</label>
                    <input type="text" id="state" name="state" class="form-control" placeholder="Enter State" value="{{ $company->state }}" >
                </div>
                 <div class="mb-3 col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" id="country" name="country" class="form-control" placeholder="Enter Country" value="{{ $company->country }}" >
                </div>
                <div class="mb-3 col-md-6">
                    <label for="zip_code" class="form-label">Zip Code</label>
                    <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="Enter Zip Code" value="{{ $company->zip_code }}" >
                </div>
                 <div class="mb-3 col-md-6">
                        <label for="gst_number" class="form-label">GSTIN Number</label>
                        <input type="text" id="gst_number" name="gst_number" class="form-control" placeholder="Enter GSTIN Number" value="{{ $company->gst_number }}" >
                </div>
                <div class="mb-3 col-md-6">
                        <label for="lut_number" class="form-label">LUT Number</label>
                        <input type="text" id="lut_number" name="lut_number" class="form-control" placeholder="Enter LUT Number" value="{{ $company->lut_number }}" >
                </div>
                 <div class="mb-3 col-md-6">
                        <label for="euid_number" class="form-label">EUID Number</label>
                        <input type="text" id="euid_number" name="euid_number" class="form-control" placeholder="Enter EUID Number" value="{{ $company->euid_number }}" >
                </div>
                <div class="mb-3 col-md-6">
                        <label for="notes" class="form-label">Notes</label>
                        <input type="text" id="notes" name="notes" class="form-control" placeholder="Enter Notes" value="{{ $company->notes }}" >
                </div>
                <div class="mb-3">
                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                    <textarea id="terms_conditions" name="terms_conditions" class="form-control" placeholder="Enter Terms & Conditions" rows="3">{{ $company->terms_conditions }}</textarea>
                </div>
                <div class="modal-footer flex gap-2">
                    <a href="{{ route('company.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    <button class="btn btn-primary" type="submit">{{__('Update')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const label = input.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                label.classList.add('has-file');
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            label.classList.remove('has-file');
        }
    }
</script>
<script>
$(document).ready(function() {
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        
        // Required fields validation (matching controller validation)
        const requiredFields = {
            'name': 'Name is required', 
            'email': 'Email is required',
            'address': 'Address is required',
            'city': 'City is required',
            'state': 'State is required',
            'country': 'Country is required',
            'zip_code': 'Zip code is required',
            'gst_number': 'GST number is required'
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