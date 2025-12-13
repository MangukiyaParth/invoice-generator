
@extends('layouts.main')
@section('page-title', 'Dashboard')
@section('content')
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $totalInvoices }}</div>
                <div class="stat-label">Total Invoices</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">₹{{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $pendingInvoices }}</div>
                <div class="stat-label">Pending Invoices</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $paidInvoices }}</div>
                <div class="stat-label">Paid Invoices</div>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Invoices</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#Invoice</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $invoice)
                            @php
                                $totalAmount = $invoice->items->sum('total_amount');
                                $isPaid = $invoice->paid_amount >= $totalAmount;
                            @endphp
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->customer_name->name ?? 'N/A' }}</td>
                                <td>{{ $invoice->currency == 'USD' ? '$' : '₹' }}{{ number_format($totalAmount, 2) }}</td>
                                <td><span class="badge {{ $isPaid ? 'badge-success' : 'badge-warning' }}">{{ $isPaid ? 'Paid' : 'Pending' }}</span></td>
                                <td>{{ $invoice->invoice_date }}</td>
                                <td>
                                    @if($invoice->type == 1)
                                    <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @else
                                    <a href="{{ route('non.gst.invoice.edit', $invoice->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No invoices found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid #007bff;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.badge-success {
    background-color: #28a745;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}
</style>
@endpush
@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid #007bff;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.badge-success {
    background-color: #28a745;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}
</style>
@endpush