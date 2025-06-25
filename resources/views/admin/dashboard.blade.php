@extends('admin.master')

@section('title', 'Dashboard')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Boxes -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Products -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="product-count">0</h3>
                        <p>Products</p>
                    </div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <a href="/admin/products" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Orders -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="order-count">0</h3>
                        <p>Orders</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-basket"></i></div>
                    <a href="/admin/orders" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Users -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="user-count">0</h3>
                        <p>Users</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <a href="/admin/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Revenue -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="revenue-count">₹0.00</h3>
                        <p>Revenue</p>
                    </div>
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    <a href="/admin/reports" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function fetchDashboardStats() {
        fetch("{{ secure_url('/admin/dashboard/stats') }}")

            .then(response => response.json())
            .then(data => {
                document.getElementById('product-count').textContent = data.products;
                document.getElementById('order-count').textContent = data.orders;
                document.getElementById('user-count').textContent = data.users;
                document.getElementById('revenue-count').textContent = '₹' + data.revenue;
            })
            .catch(error => console.error('Error fetching dashboard stats:', error));
    }

    // Initial fetch + update every 10 seconds
    fetchDashboardStats();
    setInterval(fetchDashboardStats, 10000);
</script>
@endpush