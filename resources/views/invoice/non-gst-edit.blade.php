@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Invoice</h3>
                    </div>
                    <div class="card-body">
                        <form id="invoiceForm" method="POST" action="{{ route('non.gst.invoice.update', $invoice->id) }}">
                            @csrf
                            @method('PUT')
                            <!-- Top Section: Company & Customer Selection,Invoice Details -->
                            <div class="row row-gap">
                                <!-- Left Side: Company & Customer Selection -->
                                <div class="col-xl-6 col-12">
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-3">
                                            <label class="form-label">Company</label>
                                            <select class="form-select" name="company" id="companySelect">
                                                <option value="">Select Company</option>
                                                 @foreach($companies ?? [] as $company)
                                                    <option value="{{ $company->id }}" {{ $invoice->company == $company->id ? 'selected' : '' }}>{{ $company->name }}{{ $company->nick_name ? ' - ' . $company->nick_name : '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 mb-3">
                                            <label class="form-label">Customer</label>
                                            <select class="form-select" name="customer" id="customerSelect">
                                                <option value="">Select Customer</option>
                                                @foreach($customers ?? [] as $customer)
                                                    <option value="{{ $customer->id }}" {{ $invoice->customer == $customer->id ? 'selected' : '' }}>{{ $customer->name }}{{ $customer->nick_name ? ' - ' . $customer->nick_name : '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side: Invoice Details -->
                                <div class="col-xl-6 col-12">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Invoice Date</label>
                                            <input type="date" class="form-control" name="invoice_date"
                                                value="{{ $invoice->invoice_date }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Due Date</label>
                                            <input type="date" class="form-control" name="due_date"
                                                value="{{ $invoice->due_date }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Currency</label>
                                            <select class="form-select" name="currency" id="currencySelect" required>
                                                <option value="INR" {{ $invoice->currency == 'INR' ? 'selected' : '' }}>₹ Rupees (INR)</option>
                                                <option value="USD" {{ $invoice->currency == 'USD' ? 'selected' : '' }}>$ Dollar (USD)</option>
                                                <option value="AUD" {{ $invoice->currency == 'AUD' ? 'selected' : '' }}>AU$ Australian Dollar (AUD)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Invoice Number</label>
                                            <input type="text" class="form-control" name="invoice_number"
                                                id="invoice_number" value="{{ $invoice->invoice_number }}"
                                                placeholder="Invoice number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <!-- Items Section -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Invoice Items</h5>
                                        <button type="button" class="btn btn-success" id="addItem">Add Item</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="itemsTable">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Amount <span id="currencySymbol">{{ $invoice->currency == 'USD' ? '$' : ($invoice->currency == 'AUD' ? 'AU$' : '₹') }}</span></th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itemsBody">
                                                @foreach($invoice->items as $index => $item)
                                                <tr class="item-row">
                                                    <td><input type="text" class="form-control"
                                                            name="items[{{ $index }}][description]" value="{{ $item->description }}" required></td>
                                                    <td><input type="number" class="form-control total-amount"
                                                            name="items[{{ $index }}][total_amount]" value="{{ $item->total_amount }}" step="0.01" min="0" required></td>
                                                    <td>
                                                        @if($index > 0)
                                                        <button type="button" class="btn btn-danger btn-sm remove-item" data-item-id="{{ $item->id }}">Remove</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Payment Section -->
                                    <div class="row mt-3">
                                        <div class="col-md-7"></div>
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label">Payment Made</label>
                                                <input type="number" class="form-control" id="paymentMade"
                                                    name="payment_made" step="0.01" min="0" value="{{ $invoice->payment_made ?? 0 }}"
                                                    placeholder="Enter payment amount">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="row mt-4">
                                        <div class="col-md-7"></div>
                                        <div class="col-md-5">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-end border-0"><strong>Sub Total</strong></td>
                                                        <td class="text-end border-0"><span
                                                                id="summarySymbol">{{ $invoice->currency == 'USD' ? '$' : ($invoice->currency == 'AUD' ? 'AU$' : '₹') }}</span><span
                                                                id="subTotal">0.00</span></td>
                                                    </tr>
                                                    <tr class="border-top">
                                                        <td class="text-end"><strong>Total Amount</strong></td>
                                                        <td class="text-end"><strong><span id="totalSymbol">{{ $invoice->currency == 'USD' ? '$' : ($invoice->currency == 'AUD' ? 'AU$' : '₹') }}</span><span
                                                                    id="grandTotal">0.00</span></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end border-0"><strong>Payment Made</strong></td>
                                                        <td class="text-end border-0"><span
                                                                id="paymentSymbol">{{ $invoice->currency == 'USD' ? '$' : ($invoice->currency == 'AUD' ? 'AU$' : '₹') }}</span><span
                                                                id="paymentAmount">0.00</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end border-0"><strong>Balance Due</strong></td>
                                                        <td class="text-end border-0"><span
                                                                id="balanceSymbol">{{ $invoice->currency == 'USD' ? '$' : ($invoice->currency == 'AUD' ? 'AU$' : '₹') }}</span><span
                                                                id="balanceDue">0.00</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end border-0"><strong>Total in Words</strong></td>
                                                        <td class="text-end border-0"><em id="totalInWords">Zero Only</em>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="modal-footer flex gap-2">
                                <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        input[readonly] {
            background-color: #dde2e7 !important;
        }
    </style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let itemIndex = {{ count($invoice->items) }};

    // Calculate totals
    function calculateSummary() {
        const rows = document.querySelectorAll('.item-row');
        let subTotal = 0;

        rows.forEach(row => {
            const val = parseFloat(row.querySelector('.total-amount').value) || 0;
            subTotal += val;
        });

        const payment = parseFloat(document.getElementById('paymentMade').value) || 0;
        const balance = subTotal - payment;
        const symbol = getCurrencySymbol(document.getElementById('currencySelect').value);

        document.getElementById('subTotal').textContent = subTotal.toFixed(2);
        document.getElementById('grandTotal').textContent = subTotal.toFixed(2);
        document.getElementById('paymentAmount').textContent = payment.toFixed(2);
        document.getElementById('balanceDue').textContent = balance.toFixed(2);

        document.getElementById('summarySymbol').textContent = symbol;
        document.getElementById('totalSymbol').textContent = symbol;
        document.getElementById('paymentSymbol').textContent = symbol;
        document.getElementById('balanceSymbol').textContent = symbol;

        document.getElementById('totalInWords').textContent = numberToWords(subTotal);
    }

    // Get currency symbol
    function getCurrencySymbol(currency) {
        return currency === 'USD' ? '$' : currency === 'AUD' ? 'AU$' : '₹';
    }

    // Convert number to words
    function numberToWords(amount) {
        const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        function convertHundreds(num) {
            let result = '';
            if (num >= 100) {
                result += ones[Math.floor(num / 100)] + ' Hundred ';
                num %= 100;
            }
            if (num >= 20) {
                result += tens[Math.floor(num / 10)] + ' ';
                num %= 10;
            } else if (num >= 10) {
                result += teens[num - 10] + ' ';
                num = 0;
            }
            if (num > 0) {
                result += ones[num] + ' ';
            }
            return result;
        }
        
        if (amount === 0) return 'Zero Only';
        
        const currency = document.getElementById('currencySelect').value;
        let rupees = Math.floor(amount);
        const paise = Math.round((amount - rupees) * 100);
        
        let result = '';
        
        if (rupees >= 10000000) {
            result += convertHundreds(Math.floor(rupees / 10000000)) + 'Crore ';
            rupees %= 10000000;
        }
        if (rupees >= 100000) {
            result += convertHundreds(Math.floor(rupees / 100000)) + 'Lakh ';
            rupees %= 100000;
        }
        if (rupees >= 1000) {
            result += convertHundreds(Math.floor(rupees / 1000)) + 'Thousand ';
            rupees %= 1000;
        }
        if (rupees > 0) {
            result += convertHundreds(rupees);
        }
        
        const currencyName = currency === 'USD' ? 'Dollar' : currency === 'AUD' ? 'Dollar' : 'Rupee';
        const subUnit = currency === 'USD' ? 'Cent' : currency === 'AUD' ? 'Cent' : 'Paise';
        
        result = (currency === 'USD' ? 'US ' : currency === 'AUD' ? 'Australian ' : 'Indian ') + currencyName + ' ' + result.trim();
        
        if (paise > 0) {
            result += ' and ' + convertHundreds(paise).trim() + ' ' + subUnit;
        }
        
        return result + ' Only';
    }

    // Add New Item
    document.getElementById('addItem').addEventListener('click', function () {
        const tbody = document.getElementById('itemsBody');
        const newRow = `
            <tr class="item-row">
                <td><input type="text" class="form-control" name="items[${itemIndex}][description]" required></td>
                <td><input type="number" class="form-control total-amount" step="0.01" min="0" name="items[${itemIndex}][total_amount]" required></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', newRow);
        itemIndex++;
    });

   
    // Remove item row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            if (document.querySelectorAll('.item-row').length > 1) {
                const itemId = e.target.getAttribute('data-item-id');
                const row = e.target.closest('tr');
                
                if (itemId) {
                    // Show SweetAlert confirmation for existing items
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You want to delete this item?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Continue',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('/invoice/item') }}/${itemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                }
                                throw new Error('Network response was not ok');
                            })
                            .then(data => {
                                if (data.success) {
                                    row.remove();
                                    calculateSummary();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        }
                    });
                } else {
                    // Directly remove new items
                    row.remove();
                    calculateSummary();
                }
            }
        }
    });

    // Event listeners
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('total-amount') || e.target.id === 'paymentMade') {
            calculateSummary();
        }
    });

    document.getElementById('currencySelect').addEventListener('change', function () {
        const symbol = getCurrencySymbol(this.value);
        document.getElementById('currencySymbol').textContent = symbol;
        calculateSummary();
    });

    document.querySelector('input[name="invoice_date"]').addEventListener('change', function () {
        document.querySelector('input[name="due_date"]').value = this.value;
    });

    calculateSummary();
});
</script>
<script>
$('#invoiceForm').on('submit', function(e) {
    let isValid = true;
    
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    
    const requiredFields = {
        'company': 'Company is required',
        'customer': 'Customer is required',
        'invoice_date': 'Invoice date is required',
        'due_date': 'Due date is required'
    };
    
    $.each(requiredFields, function(field, message) {
        const input = $('[name="' + field + '"]');
        const value = input.val() ? input.val().trim() : '';
        
        if (value === '') {
            input.addClass('is-invalid');
            input.closest('.form-group, .mb-3, .col-md-6').append('<div class="error-message text-danger mt-1">' + message + '</div>');
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush