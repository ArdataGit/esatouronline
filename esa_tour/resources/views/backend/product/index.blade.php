@extends('layouts.backend.app')
@section('product', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <!-- DataTable with Buttons -->
            <div class="card">
                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.product.create') }}" type="button" class="btn btn-primary">
                        <i class="ti ti-plus me-sm-1"></i>Tambah
                    </a>
                </div>
                
                <div class="card-datatable table-responsive pt-0">
                    <table id="product-table" class="datatables-basic table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori Produk</th>
                                <th>Penjualan</th>
                                <th>Pembelian</th>
                                <th>Profit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/backend/product.js') }}"></script>
    <script>
        var deleteUrl = '{{ route("backend.product.destroy", ":id") }}';
    </script>
@endpush

