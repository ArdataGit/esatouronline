@extends('layouts.backend.app')
@section('sale', 'active')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>
        <div class="col-md-3 mb-4">
            <div>
                <a href="{{ route('backend.sale.export', ['start_date' => $start_date, 'end_date' => $end_date]) }}" target="__blank" style="margin-top: 10%;" class="btn btn-info">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-spreadsheet"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="row">
            <form action="{{ route('backend.sale.search') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3 mb-4">
                    <label for="select2Basic" class="form-label">Tanggal Awal</label><span style="color: red;">*</span>
                    <input id="date" type="date" name="start_date" class="form-control" id="floatingInput"
                        aria-describedby="floatingInputHelp"/>
                </div>

                <div class="col-md-3 mb-4">
                    <label for="select2Basic" class="form-label">Tanggal Akhir</label><span style="color: red;">*</span>
                    <input id="date" type="date" name="end_date" class="form-control" id="floatingInput"
                        aria-describedby="floatingInputHelp"/>
                </div>

                <div class="col-md-3 mb-4">
                    <button type="submit" style="margin-top: 12%;" class="btn btn-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table id="bank-table" class="datatables-basic table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori Produk</th>
                            <th>Nominal</th>
                        </tr>

                        @foreach ($invoice as $item)
                            @php 
                                $total = 0;
                            @endphp

                            <tr>
                                <td>
                                    {{ $item['date_publisher'] }}
                                </td>
                                <td>
                                    {{ $item['categories'][0]['product_category'] }}
                                </td>
                                <td>
                                    {{number_format($item['categories'][0]['price'], 2)  }} 
                                </td>
                            </tr>

                            @php $total += $item['categories'][0]['price']; @endphp

                            @if(count($item['categories']) > 1)
                                @for ($i = 1; $i < count($item['categories']); $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{  $item['categories'][$i]['product_category'] }}</td>
                                        <td>{{ number_format($item['categories'][$i]['price'], 2) }}</td>
                                    </tr>

                                    @php $total += $item['categories'][$i]['price']; @endphp
                                @endfor
                            @endif     
                            
                            <tr>
                                <td>
                                    
                                </td>
                                <td>
                                    <b>Total</b>
                                </td>
                                <td>
                                    <b>{{number_format($total, 2)  }} </b> 
                                </td>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush
