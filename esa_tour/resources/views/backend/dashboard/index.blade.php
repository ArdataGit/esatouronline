@extends('layouts.backend.app')
@section('home', 'active')
@section('content')
    <!-- / Navbar -->

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <!-- Sales last year -->
                <!-- Total Profit -->
                <div class="col-xl-3 col-md-4 col-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <div class="badge p-2 mb-2 rounded"
                                    style="background-color: #FFC55A; border-radius: 1rem !important;">
                                    <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                                </div>

                                <h5 class="card-title mb-1 pt-2">Pemasukan Hari Ini</h5>
                            </center>

                            <center>
                                <p class="mb-2 mt-1" style="color: #FFC55A; font-size: 20px;">Rp. {{ number_format($today_income, 2) }}</p>
                            </center>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-4 col-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <div class="badge p-2 mb-2 rounded"
                                    style="background-color: #FFA21D; border-radius: 1rem !important;">
                                    <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                                </div>

                                <h5 class="card-title mb-1 pt-2">Pemasukan Bulan Ini</h5>
                            </center>

                            <center>
                                <p class="mb-2 mt-1" style="color: #FFA21D; font-size: 20px;">Rp. {{ number_format($month_income, 2) }}</p>
                            </center>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-4 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <div class="badge p-2 mb-2 rounded"
                                    style="background-color: #5BD1DC; border-radius: 1rem !important;">
                                    <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                                </div>

                                <h5 class="card-title mb-1 pt-2">Pengeluaran Hari Ini</h5>
                            </center>

                            <center>
                                <p class="mb-2 mt-1" style="color: #5BD1DC; font-size: 20px;">Rp. {{ number_format($today_expense, 2) }}</p>
                            </center>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-4 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <div class="badge p-2 mb-2 rounded"
                                    style="background-color: #FF3A6E; border-radius: 1rem !important;">
                                    <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                                </div>

                                <h5 class="card-title mb-1 pt-2">Pengeluaran Bulan Ini</h5>
                            </center>

                            <center>
                                <p class="mb-2 mt-1" style="color: #FF3A6E; font-size: 20px;">Rp. {{ number_format($month_expense, 2) }}</p>
                            </center>
                        </div>
                    </div>
                </div>

                <div class="col-6 order-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Invoice Terbaru</h5>
                            </div>
                            {{-- <div class="dropdown">
                                <button class="btn p-0" type="button" id="routeVehicles" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div> --}}
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="dt-route-vehicles table">
                                <thead class="border-top">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Pelanggan</th>
                                        <th>Item</th>
                                        <th>Produk</th>
                                    </tr>
                                    
                                    @foreach ($invoice as $item)
                                        @php 
                                            $invoice_d = App\Models\InvoiceDetail::where('invoice_id', $item->id)->get();
                                            // dd($invoice_d[0]->category);
                                        @endphp

                                        <tr>
                                            <td>
                                                <button class="btn btn-sm btn-info">{{ $item->invoice_number }}</button>
                                            </td>
                                            <td>{{ $item->date_publisher }}</td>
                                            <td>{{ $item->customer->name }}</td>
                                            <td>
                                                @if (isset($invoice_d[0]))
                                                {{ $invoice_d[0]->category->product_category }} 
                                                @else 
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($invoice_d[0]))
                                                    {{ $invoice_d[0]->product_name }} 
                                                @else 
                                                    -
                                                @endif
                                            </td>
                                        </tr>

                                        @if (isset($invoice_d))
                                            @for ($i = 1; $i < count($invoice_d); $i++)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $invoice_d[$i]->category->product_category }} </td>
                                                    <td>{{ $invoice_d[$i]->product_name }} </td>
                                                </tr>
                                            @endfor
                                        @endif
                                    
                                
                                    @endforeach
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-6 order-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                {{-- <h5 class="m-0 me-2">Statistik Mingguan Inovice</h5> --}}
                                <button id="weekly-stats-btn" class="btn btn-sm btn-warning">Statistik Mingguan Inovice</button>
                                <button id="monthly-stats-btn" class="btn btn-sm btn-default">Statistik Bulanan Inovice</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">                            
                                    <dl class="row mb-0">
                                        <hr>
                                        <dt class="col-6 fw-normal text-heading">Total Inovice Dibuat</dt>
                                        <dd id="total_invoice" class="col-6 text-end total_invoice">
                                            Rp 0
                                        </dd>
                                        

                                        <hr>
                                        <dt class="col-6 fw-normal text-heading">Total Dibayar</dt>
                                        <dd id="total_pay" class="col-6 text-end total_pay">
                                            Rp 0
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <script>
        $(document).ready(function() {
            statistik_default();

            $('#monthly-stats-btn').click(function() {
                $(this).removeClass('btn-default').addClass('btn-warning');
                $('#weekly-stats-btn').removeClass('btn-warning').addClass('btn-default');

                $.ajax({
                    url: "{{ route('backend.dashboard.total_invoice') }}",
                    type: 'GET',
                    dataType: 'json',
                    data:{
                        'filter': 'monthly',
                    },
                    success: function (data) {
                        var total_invoice = document.getElementById("total_invoice");
                        var total_pay = document.getElementById("total_pay");

                        if (total_invoice) {
                            total_invoice.textContent = "Rp. " + numeral(data.total_invoice).format('0,0');
                        }

                        if (total_pay) {
                            total_pay.textContent = "Rp. " + numeral(data.total_pay).format('0,0');
                        }
                    },
                });
            });

            $('#weekly-stats-btn').click(function() {
                // Toggle classes
                $(this).removeClass('btn-default').addClass('btn-warning');
                $('#monthly-stats-btn').removeClass('btn-warning').addClass('btn-default');

                $.ajax({
                    url: "{{ route('backend.dashboard.total_invoice') }}",
                    type: 'GET',
                    dataType: 'json',
                    data:{
                        'filter': 'weekly',
                    },
                    success: function (data) {
                        var total_invoice = document.getElementById("total_invoice");
                        var total_pay = document.getElementById("total_pay");

                        if (total_invoice) {
                            total_invoice.textContent = "Rp. " + numeral(data.total_invoice).format('0,0');
                        }

                        if (total_pay) {
                            total_pay.textContent = "Rp. " + numeral(data.total_pay).format('0,0');
                        }
                    },
                });
            });

            function statistik_default()
            {
                $.ajax({
                    url: "{{ route('backend.dashboard.total_invoice') }}",
                    type: 'GET',
                    dataType: 'json',
                    data:{
                        'filter': 'weekly',
                    },
                    success: function (data) {
                        var total_invoice = document.getElementById("total_invoice");
                        var total_pay = document.getElementById("total_pay");

                        if (total_invoice) {
                            total_invoice.textContent = "Rp. " + numeral(data.total_invoice).format('0,0');
                        }

                        if (total_pay) {
                            total_pay.textContent = "Rp. " + numeral(data.total_pay).format('0,0');
                        }
                    },
                });
            }
        });
    </script>
@endpush
