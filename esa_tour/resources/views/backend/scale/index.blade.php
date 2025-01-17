@extends('layouts.backend.app')
@section('scale', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <div style="margin-top: -4%;">
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

                                <form action="{{ route('backend.scale.search') }}" method="POST" class="row g-3">
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
                            <h6 class="card-title m-0">Aktiva</h6>
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
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Aktiva Lancar</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_income = 0; @endphp
                                    @foreach ($bank as $item)    
                                        <tr>
                                            <td style="text-align: center;">{{ $item->bank_name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->income, 2) }}</td>

                                            @php $total_income += $item->income; @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_income, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Piutang Usaha</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_piutang = 0; @endphp
                                    <tr>   
                                        <td style="text-align: center;">Piutang</td>
                                        <td style="text-align: center;">Rp. {{ number_format($piutang, 2) }}</td>

                                        @php $total_piutang += $piutang; @endphp
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_piutang, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Deposit</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    @php $total_deposit = 0; @endphp
                                    @foreach ($deposit as $item)
                                        <tr>   
                                            <td style="text-align: center;">{{ $item->name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->income, 2) }}</td>

                                            @php $total_deposit += $item->income; @endphp
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_deposit, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    @php $total_all = $total_income + $total_piutang + $total_deposit; @endphp
                                    <tr>
                                        <th style="text-align: center;">Total</th>
                                        <th style="color: green; text-align: center;">Rp. {{ number_format($total_all, 2) }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Pasiva</h6>
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
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Hutang Usaha</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_hutang = 0; @endphp
                                    @foreach ($hutang as $item)    
                                        <tr>
                                            <td style="text-align: center;">Hutang {{ $item->product_category }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->debt_to_vendors, 2) }}</td>

                                            @php $total_hutang += $item->debt_to_vendors; @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_hutang, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Modal</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_asset = 0; @endphp
                                    @foreach ($asset as $item)    
                                        <tr>
                                            <td style="text-align: center;">Hutang {{ $item->name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->nominal, 2) }}</td>

                                            @php $total_asset += $item->nominal; @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_asset, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 28%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Saldo Laba</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_profit = 0; @endphp   
                                    <tr>
                                        <td style="text-align: center;">Laba Tahun Berjalan</td>
                                        <td style="text-align: center;">Rp. {{ number_format($profit, 2) }}</td>

                                        @php $total_profit += $profit; @endphp
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_profit, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    @php $total_all_pasiva = $total_hutang + $total_asset + $total_profit; @endphp
                                    <tr>
                                        <th style="text-align: center;">Total</th>
                                        <th style="color: green; text-align: center;">Rp. {{ number_format($total_all_pasiva, 2) }}</th>
                                    </tr>
                                </thead>
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

