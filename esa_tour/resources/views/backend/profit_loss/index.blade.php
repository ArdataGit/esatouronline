@extends('layouts.backend.app')
@section('profit_loss', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <div>
                        <a href="{{ route('backend.profit_loss.export', ['periode' => $periode, 'start_date' => $start_date, 'end_date' => $end_date]) }}" target="__blank" style="margin-top: 10%;" class="btn btn-info">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                            Export Excel
                        </a>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div style="margin-left: -35%; margin-top: -4%;">
                        <button id="filter-button" style="margin-top: 10%;" type="submit" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_filter">
                         <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" /></svg>
                            Filter Tanggal
                        </button>
                    </div>
                </div>

                <div class="modal fade" id="modal_filter" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xs modal-simple modal-edit-user">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mb-4">
                                    <h3 class="mb-2">Filter</h3>
                                </div>

                                <form action="{{ route('backend.profit_loss.search') }}" method="POST" class="row g-3">
                                    @csrf

                                    <label class="form-label"><b>Pilih Periode</b></label></span>

                                    <div class="col-12 col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input
                                              class="form-check-input"
                                              type="radio"
                                              name="periode"
                                              id="inlineRadio1"
                                              value="last_1_month" />
                                            <label class="form-check-label" for="inlineRadio1">1 bulan terakhir</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="periode"
                                                id="periode"
                                                value="last_1_year" />
                                            <label class="form-check-label" for="inlineRadio2">1 tahun terakhir</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="periode"
                                                id="periode"
                                                value="last_1_semester" />
                                            <label class="form-check-label" for="inlineRadio2">1 semester terakhir</label>
                                        </div>
                                    </div>

                                    <label class="form-label"><b>Custom Periode</b></label></span>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="modalEditUserFirstName">Tanggal Awal</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"/>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="modalEditUserFirstName">Tanggal Akhir</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control"/>
                                    </div>
                                   
                                    <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 5%;">
                                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                                            Batal
                                        </button>
        
                                        <button id="submit_customer" type="submit" class="btn btn-warning me-sm-3 me-1">Buat</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
          

            <div class="row">
                <!-- DataTable with Buttons -->
                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Penjualan</h6>
                            <h6 class="m-0">
                                @if ($periode == null)
                                    @if ($start_date == null && $end_date == null)
                                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                    @else 
                                        {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
                                    @endif
                                @endif
                            </h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Kategori</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_sale = 0; @endphp
                                    @foreach ($invoice as $item)                                       
                                        <tr>
                                            <td style="text-align: center;">{{ $item->product_category }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->selling_price, 2) }}</td>

                                            @php $total_sale += $item->selling_price; @endphp
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center;">Rp. {{ number_format($total_sale, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Retur Penjualan Tiket</td>
                                        <td style="text-align: center; color:red;">-Rp. @if($retur_sale) {{ number_format($retur_sale->price, 2) }} @else 0 @endif</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">PPN</td>
                                        <td style="text-align: center; color:red;">-Rp. @if($ppn) {{ number_format($ppn->ppn, 2) }} @else 0 @endif</td>
                                    </tr>

                                    @php 
                                        $nominal_ppn = 0;
                                        $nominal_retur_sale = 0;

                                        if ($ppn) {
                                            $nominal_ppn = $ppn->ppn;
                                        }

                                        if ($retur_sale) {
                                            $nominal_retur_sale = $retur_sale->price;
                                        }

                                        $final_sale = $total_sale - $nominal_ppn - $nominal_retur_sale;
                                    
                                    @endphp

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_sale, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Harga Pokok Pembelian</h6>
                            <h6 class="m-0">
                                @if ($periode == null)
                                    @if ($start_date == null && $end_date == null)
                                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                    @else 
                                        {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
                                    @endif
                                @endif
                            </h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Kategori</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_purchase = 0; @endphp
                                    @foreach ($invoice as $item)                                       
                                        <tr>
                                            <td style="text-align: center;">{{ $item->product_category }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->purchase_price, 2) }}</td>

                                            @php $total_purchase += $item->purchase_price; @endphp
                                        </tr>
                                    @endforeach

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center;">Rp. {{ number_format($total_purchase, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Retur Pembelian Tiket</td>
                                        <td style="text-align: center; color:red;">-Rp. @if($retur_purchase) {{ number_format($retur_purchase->price, 2) }} @else 0 @endif</td>
                                    </tr>

                                    @php 
                                        $nominal_retur_purchase = 0;

                                        if ($retur_purchase) {
                                            $nominal_retur_purchase = $retur_purchase->price;
                                        }

                                        $final_purchase = $total_purchase - $nominal_retur_purchase;     
                                    @endphp
                                    
                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_purchase, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Laba Kotor</h6>
                            <h6 class="m-0">
                                @if ($periode == null)
                                    @if ($start_date == null && $end_date == null)
                                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                    @else 
                                        {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
                                    @endif
                                @endif
                            </h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                {{-- <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Penjualan</th>
                                        <th style="text-align: center;">Harga Pokok Pembelian</th>
                                    </tr>
                                </thead> --}}
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">Penjualan</td>
                                        <td style="text-align: center;">Rp. {{ number_format($final_sale, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Harga Pokok Pembelian</td>
                                        <td style="text-align: center; color:red;">-Rp. {{ number_format($final_purchase, 2) }}</td>
                                    </tr>

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_sale - $final_purchase, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Biaya Operasional</h6>
                            <h6 class="m-0">
                                @if ($periode == null)
                                    @if ($start_date == null && $end_date == null)
                                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                    @else 
                                        {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
                                    @endif
                                @endif
                            </h6>
                        </div>

                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Kategori</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_expense = 0; @endphp

                                    @foreach ($expense as $item)                                       
                                        <tr>
                                            <td style="text-align: center;">{{ $item->name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->nominal, 2) }}</td>

                                            @php $total_expense += $item->nominal; @endphp
                                        </tr>
                                    @endforeach

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center;">Rp. {{ number_format($total_expense, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Laba Bersih Perusahaan</h6>
                            <h6 class="m-0"></h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">Harga Pokok Pembelian</td>
                                        <td style="text-align: center;">Rp. {{ number_format($final_purchase, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Biaya Operasional</td>
                                        <td style="text-align: center; color:red;">-Rp. {{ number_format($total_expense, 2) }}</td>
                                    </tr>

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_purchase - $total_expense, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <script src="{{ asset('assets/js/backend/tax.js') }}"></script>
    <script>
        var deleteUrl = '{{ route("backend.tax.destroy", ":id") }}';
    </script> --}}
@endpush

