@extends('admin.master')

@section('title', 'Orders')

@push('styles')
<!-- DataTables CSS (Bootstrap 5 + Responsive) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0 text-dark">Orders</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order List</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="orders-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Total (₹)</th>
                                <th>Status</th>
                                <th>Coupon</th>
                                <th>Created</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? '—' }}</td>
                                <td>{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>{{ $order->coupon?->code ?? '-' }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info mb-1">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning mb-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<!-- jQuery and DataTables JS with Responsive extension -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#orders-table').DataTable({
            paging: true,
            ordering: true,
            searching: true,
            responsive: true,
            info: true,
            order: [
                [0, 'desc']
            ]
        });
    });
</script>
@endpush