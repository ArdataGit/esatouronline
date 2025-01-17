@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <!-- DataTable with Buttons -->
            <div class="card">
                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.invoice.create') }}" type="button" class="btn btn-primary">
                        <i class="ti ti-plus me-sm-1"></i>Tambah
                    </a>
                </div>
                
                <div class="card-datatable table-responsive pt-0">

                    <table id="invoice-table" class="datatables-basic table">
                        <thead>
                            <tr>
                                {{-- <th></th>
                                <th></th> --}}
                                <th>ID</th>
                                <th>Invoice</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Penerbitan</th>
                                <th>Invoice Fisik</th>
                                {{-- <th>Item</th>
                                <th>Produk</th>
                                <th>Keterangan</th>
                                <th>Harga Jual</th>
                                <th>NTA</th> --}}
                                {{-- <th>Dari Bank</th> --}}
                                <th>Status Cetak</th>
                                <th>Status Invoice</th>
                                <th>Status Piutang</th>
                                <th>Status Hutang</th>
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
    <script src="{{ asset('assets/js/backend/invoice.js?updated=121331') }}"></script>
    <script>
        var deleteUrl = '{{ route("backend.invoice.destroy", ":id") }}';
    </script>
@endpush

