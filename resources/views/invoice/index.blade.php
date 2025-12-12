@extends('layouts.main')
@section('page-title', 'Invoice')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"></h5>
        <div>
            <a href="{{ route('invoice.create') }}" class="btn btn-success">GST Invoice</a>
            <a href="{{ route('invoice.non-gst') }}" class="btn btn-primary" style="margin-left: 3px;">Non-GST Invoice</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Invoice</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table" id="invoice-table">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Company</th>
                        <th>Customer</th>
                        <th>Invoice Date</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('invoice.index') }}',
            columns: [
                {data: 'invoice_number', name: 'invoice_number' },
                {data: 'company', name: 'company'},
                {data: 'customer', name: 'customer'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'due_date', name: 'due_date'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });
        
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const deleteUrl = $(this).attr('href');
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': deleteUrl
                    });
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

