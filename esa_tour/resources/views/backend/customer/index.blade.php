@extends('layouts.backend.app')
@section('customer', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <!-- DataTable with Buttons -->
            <div class="card">
                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.customer.create') }}" type="button" class="btn btn-primary">
                        <i class="ti ti-plus me-sm-1"></i>Tambah
                    </a>
                </div>
                
                <div class="card-datatable table-responsive pt-0">
                    <table id="customer-table" class="datatables-basic table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No. Telpon</th>
                                <th>Invoice</th>
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
    {{-- <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script> --}}
    <script src="{{ asset('assets/js/backend/customer.js') }}"></script>
    <script>
        var deleteUrl = '{{ route("backend.customer.destroy", ":id") }}';
    </script>
@endpush

