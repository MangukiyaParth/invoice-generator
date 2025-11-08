@extends('layouts.main')

@section('page-title', 'Company')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"></h5>
            <a href="{{ route('company.create') }}" class="btn btn-success">Add New</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Company</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table" id="company-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>GST Number</th>
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
        $('#company-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('company.index') }}',
            columns: [
                { data: 'logo', name: 'logo', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                {data: 'email', name: 'email'},
                {data: 'gst_number', name: 'gst_number'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });
        
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const deleteUrl = $(this).attr('data-url');
            
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

