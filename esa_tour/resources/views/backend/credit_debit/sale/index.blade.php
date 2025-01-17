@extends('layouts.backend.app')
@section('sale', 'active')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

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
                                $invoice_d = App\Models\InvoiceDetail::select('invoice.date_publisher', Illuminate\Support\Facades\DB::raw('SUM(invoice_d.selling_price) as price'), 'products.product_category')
                                            ->join('invoice', 'invoice_d.invoice_id', 'invoice.id')
                                            ->join('products', 'invoice_d.category_id', 'products.id')
                                            ->where('invoice.date_publisher', $item->date_publisher)
                                            ->groupBy('invoice.date_publisher', 'products.product_category')
                                            ->get();
                                // dd($invoice_d);
                            @endphp

                            <tr>
                                <td>
                                    {{ $item->date_publisher }}
                                </td>
                                <td>
                                    @if (isset($invoice_d[0]))
                                        {{ $invoice_d[0]->product_category }} 
                                    @else 
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($invoice_d[0]))
                                        {{number_format($invoice_d[0]->price, 2)  }} 
                                    @else 
                                        -
                                    @endif
                                </td>
                            </tr>

                            @if (isset($invoice_d))
                                @for ($i = 1; $i < count($invoice_d); $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $invoice_d[$i]->product_category }}</td>
                                        <td>{{ number_format($invoice_d[$i]->price, 2) }}</td>
                                    </tr>
                                @endfor
                            @endif
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
