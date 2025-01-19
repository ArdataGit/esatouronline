@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
    <style>
        /* Style untuk header tabel */
        th {
            position: relative;
            font-size: 14px;
            /* Ukuran font header */
            background-color: #f8f9fa;
            /* Warna background default header */
            color: #495057;
            /* Warna teks */
            font-weight: bold;
        }

        /* Tambahkan border untuk lebih menonjol */
        th {
            border-bottom: 3px solid #dee2e6;
            /* Garis bawah header */
        }

        /* Tambahkan ikon lebih jelas untuk tombol sort */
        th.sorting:after,
        th.sorting_asc:after,
        th.sorting_desc:after {
            font-family: 'Font Awesome 5 Free';
            /* Gunakan FontAwesome */
            font-weight: 900;
            font-size: 16px;
            /* Ukuran ikon lebih besar */
            position: absolute;
            right: 15px;
            /* Posisi ikon */
            top: 50%;
            transform: translateY(-50%);
        }

        /* Ikon default (belum diurutkan) */
        th.sorting:after {
            content: '\f0dc';
            /* Ikon 'Sort' */
            color: gray;
        }

        /* Ikon ascending */
        th.sorting_asc:after {
            content: '\f062';
            /* Ikon 'Sort Ascending' */
            color: green;
            /* Warna lebih terang */
        }

        /* Ikon descending */
        th.sorting_desc:after {
            content: '\f063';
            /* Ikon 'Sort Descending' */
            color: red;
            /* Warna lebih terang */
        }

        /* Hover effect untuk header */
        th:hover {
            background-color: #e9ecef;
            /* Warna lebih terang saat hover */
            color: #212529;
            /* Warna teks lebih gelap */
            cursor: pointer;
            /* Ubah cursor menjadi pointer */
        }

        /* Kolom yang sedang diurutkan */
        table.dataTable th.sorting_asc,
        table.dataTable th.sorting_desc {
            background-color: #d1ecf1;
            /* Warna biru muda */
            color: #0c5460;
            /* Warna teks lebih gelap */
        }
    </style>


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
        var deleteUrl = '{{ route('backend.invoice.destroy', ':id') }}';
    </script>
@endpush
