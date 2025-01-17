@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $title }} /</span> {{ $action }}</h4>

        <form action="{{ route('backend.invoice.update_details', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="status" value="{{ $data->status }}">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Invoice</h5>
                            <h5 class="m-0">{{ $data->invoice_number }}</h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title m-0"></h5>

                                <label for="" style="margin-left: 60%;">
                                    Tanggal Penerbitan : <br>
                                    {{ $data->date_publisher }}
                                </label>

                                <label for="">
                                    Tanggal Jatuh Tempo : <br>
                                    {{ $data->due_date }}
                                </label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center" style="margin-top: 10%;">
                                <label for="">
                                    <b>Ditagihkan Kepada :</b> <br>
                                    <div style="width: 60%;">
                                        {{ $data->customer->name }}, {{ $data->customer->address }}
                                    </div>
                                    {{-- <b>Nomor Pajak :</b> --}}
                                </label>

                                <label for="" style="margin-right: 40%;">
                                    <b>Dikirim Ke :</b> <br>
                                    <div style="width: 60%;">
                                        {{ $data->customer->name }}, {{ $data->customer->address }}
                                    </div>
                                    {{-- <b>Nomor Pajak :</b> --}}
                                </label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center" style="margin-top: 5%;">
                                <label for="">
                                    <b>Status : {{ $data->status_receivables }}</b> <br>
                                    @if($data->status_receivables == 'Belum Lunas')
                                        <label class="btn btn-sm btn-danger">Belum Lunas</label>
                                    {{-- @elseif($data->status == 'Void')
                                        <label class="btn btn-sm btn-danger">Void</label> --}}
                                    @else
                                        <label class="btn btn-sm btn-success">Sudah Lunas</label>
                                    @endif

                                    <br><br>
                                    <b>Ringkasan Produk</b>
                                </label>
                            </div>

                            <div class="table-responsive text-nowrap" style="margin-top: 5%;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item</th>
                                            <th>Produk</th>
                                            <th>Keterangan</th>
                                            <th>Harga Jual</th>
                                            <th>NTA</th>
                                            <th>Dari Bank</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($data_d as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->category->product_category }}</td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->note }}</td>
                                                <td>Rp. {{ number_format($item->total_price_sell) }}</td>
                                                <td>Rp. {{ number_format($item->qty * $item->purchase_price) }}</td>
                                                <td>
                                                    @if (isset($item->bank))
                                                        {{ $item->bank->bank_name }}
                                                    @else 
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <dl class="row mb-0" style="margin-top: 10%;">
                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Harga Jual</dt>
                                <dd id="price_total_selling" class="col-6 text-end price_total_selling">
                                    Rp. {{ number_format($data->price_total_selling) }}
                                </dd>

                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Modal</dt>
                                <dd id="price_total_purchase" class="col-6 text-end price_total_purchase">
                                    Rp. {{ number_format($data->price_total_purchase) }}
                                </dd>

                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Keuntungan</dt>
                                <dd id="total_profit" class="col-6 text-end total_profit">
                                    Rp. {{ number_format($data->total_profit) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Pembayaran Customer</h5>
                        </div>

                        <div class="card-body">
                            <div>
                                <label id="receivables_label" class="form-label receivables_label" style="color: red;">Piutang Customer: Rp. {{ number_format($data->receivables) }}</label>
                                <input type="hidden" id="receivables" class="receivables" value="{{ $data->price_total_selling }}">
                            </div>

                            <button id="add_payment_cust" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                <i class="ti ti-plus me-sm-1"></i>
                            </button>

                            <div id="rowsContainer">
                                @if (count($customer_payment) > 0)
                                    @foreach ($customer_payment as $key => $item)
                                        <div class="row piutang-row" style="margin-top: 10%;">
                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Nominal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="nominal" type="text" class="form-control nominal" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal[]" oninput="update_piutang(this)" value="{{ number_format($item->nominal, 0) }}"/>
                                                </div>
                                            </div>
        
                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" id="date" name="date[]" value="{{ $item->date }}"/>
                                            </div>
        
                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Keterangan</label>
                                                <textarea id="floatingInput" rows="1" class="form-control" name="note[]">{{ $item->note }}</textarea>
                                            </div>
        
                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="bank_id" @else for="bank_id_"{{ $key }} @endif  class="form-label">Ke Bank</label>
                                                <select @if($key == 0) id="bank_id" @else id="bank_id_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id[]">
                                                    <option>-- Pilih Bank --</option>
            
                                                    @foreach ($bank as $item2)                                           
                                                        <option @if($item->bank_id == $item2->id) selected @endif value="{{ $item2->id }}">{{ $item2->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @if ($key !== 0)
                                                <div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;">
                                                    <button type="button" class="btn btn-danger removeRowBtn">
                                                        <i class="ti ti-trash me-sm-1"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row piutang-row" style="margin-top: 10%;">
                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                <input id="nominal" type="text" class="form-control nominal" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal[]" oninput="update_piutang(this)"/>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date" name="date[]"/>
                                        </div>

                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Keterangan</label>
                                            <textarea id="floatingInput" rows="1" class="form-control" name="note[]"></textarea>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="bank_id" class="form-label">Ke Bank</label>
                                            <select id="bank_id" class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id[]">
                                                <option>-- Pilih Bank --</option>
        
                                                @foreach ($bank as $item)                                           
                                                    <option value="{{ $item->id }}">{{ $item->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                             
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Hutang Ke Vendor</h5>
                        </div>

                        <div class="card-body">
                            <div>
                                <div class="row debt-row" style="margin-top: 10%;">
                                    <div class="table-responsive text-nowrap" style="margin-top: 5%;">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kategori Item</th>
                                                    <th>Produk</th>
                                                    <th>Nominal</th>
                                                    <th>Status Hutang</th>
                                                    <th>Tanggal Pelunasan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                                @foreach ($data_d as $key => $item)
                                                    @if ($item->debt_to_vendors !== null)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->category->product_category }}</td>
                                                            <td>{{ $item->product_name }}</td>
                                                            <td>Rp. {{ number_format($item->debt_to_vendors) }}</td>
                                                            <td>
                                                                @if($item->status_debt == 'Belum Lunas')
                                                                    <label class="btn btn-sm btn-danger">Belum Lunas</label>
                                                                @else 
                                                                    <label class="btn btn-sm btn-success">Sudah Lunas</label>
                                                                @endif 
                                                            </td>
                                                            <td>
                                                                <input type="hidden" class="form-control" id="inv_debt_id" name="inv_debt_id[]" value="{{ $item->id }}"/>
                                                                <input type="date" class="form-control" id="date" name="payment_date[]" value="{{ $item->date_payment_debt }}"/>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Refund Customer</h5>
                        </div>

                        <div class="card-body">
                            <button id="add_refund" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                <i class="ti ti-plus me-sm-1"></i>
                            </button>


                            <div id="rowsContainerRefund">
                                @if (count($refund) > 0)
                                    @foreach ($refund as $key => $item)
                                        <div class="row refund-row" style="margin-top: 10%;">
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Nominal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="nominal_refund" type="text" class="form-control nominal_refund" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_refund[]" value="{{ number_format($item->nominal, 0) }}" oninput="format_refund(this)"/>
                                                </div>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" id="date_refund" name="date_refund[]" value="{{ $item->date }}"/>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="category_id_refund" @else for="category_id_refund_"{{ $key }} @endif class="form-label">Kategori Item</label><span style="color: red;">*</span>
                                                <select @if($key == 0) id="category_id_refund" @else id="category_id_refund_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id_refund[]" required>
                                                    <option>-- Pilih Kategori --</option>
            
                                                    @foreach ($products as $product)
                                                        <option @if($item->product_id == $product->id) selected @endif value="{{ $product->id }}">{{ $product->product_category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            
                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="refund_category" @else for="refund_category_"{{ $key }} @endif class="form-label">Kategori Refund</label><span style="color: red;">*</span>
                                                <select @if($key == 0) id="refund_category" @else id="refund_category_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="refund_category[]" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <option @if($item->refund_category == 'Refund Customer') selected @endif value="Refund Customer">Refund Customer</option>
                                                    <option @if($item->refund_category == 'Refund Supplier') selected @endif value="Refund Supplier">Refund Supplier</option>
                                                </select>
                                            </div>


                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="bank_id_refund" @else for="bank_id_refund_"{{ $key }} @endif class="form-label">Dari Bank</label>
                                                <select @if($key == 0) id="bank_id_refund" @else id="bank_id_refund_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id_refund[]">
                                                    <option>-- Pilih Bank --</option>
            
                                                    @foreach ($bank as $item2)                                           
                                                        <option @if($item->bank_id == $item2->id) selected @endif value="{{ $item2->id }}">{{ $item2->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-6 mb-6">
                                                <label for="select2Basic" class="form-label">Keterangan</label>
                                                <textarea id="note_refund" rows="3" class="form-control" name="note_refund[]">{{ $item->note }}</textarea>
                                            </div>
                                        </div> 
                                    @endforeach
                                @else 
                                    <div class="row refund-row" style="margin-top: 10%;">
                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                <input id="nominal_refund" type="text" class="form-control nominal_refund" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_refund[]" oninput="format_refund(this)"/>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date_refund" name="date_refund[]"/>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="category_id_refund" class="form-label">Kategori Item</label><span style="color: red;">*</span>
                                            <select id="category_id_refund" class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id_refund[]">
                                                <option value="">-- Pilih Kategori --</option>
        
                                                @foreach ($products as $item)
                                                    <option value="{{ $item->id }}">{{ $item->product_category }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="refund_category" class="form-label">Kategori Refund</label><span style="color: red;">*</span>
                                            <select id="refund_category" class="select2 form-select form-select-lg" data-allow-clear="true" name="refund_category[]">
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="Refund Customer">Refund Customer</option>
                                                <option value="Refund Supplier">Refund Supplier</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="bank_id_refund" class="form-label">Dari Bank</label>
                                            <select id="bank_id_refund" class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id_refund[]">
                                                <option>-- Pilih Bank --</option>
        
                                                @foreach ($bank as $item)                                           
                                                    <option value="{{ $item->id }}">{{ $item->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <label for="select2Basic" class="form-label">Keterangan</label>
                                            <textarea id="note_refund" class="form-control" name="note_refund[]" rows="3"></textarea>
                                        </div>
                                    </div> 
                                @endif
                              
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Cashback</h5>
                        </div>

                        <div class="card-body">
                            <button id="add_cashback" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                <i class="ti ti-plus me-sm-1"></i>
                            </button>


                            <div id="rowsContainerCashback">
                                @if (count($cashback) > 0)
                                    @foreach ($cashback as $key => $item)
                                        <div class="row cashback-row" style="margin-top: 10%;">
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Nominal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="nominal_cashback" type="text" class="form-control nominal_cashback" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_cashback[]" value="{{ number_format($item->nominal, 0) }}" oninput="format_cashback(this)"/>
                                                </div>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" id="date_cashback" name="date_cashback[]" value="{{ $item->date }}"/>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="category_id_cashback" @else for="category_id_cashback_"{{ $key }} @endif class="form-label">Kategori Item</label><span style="color: red;">*</span>
                                                <select @if($key == 0) id="category_id_cashback" @else id="category_id_cashback_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id_cashback[]" required>
                                                    <option>-- Pilih Kategori --</option>
            
                                                    @foreach ($products as $product)
                                                        <option @if($item->product_id == $product->id) selected @endif value="{{ $product->id }}">{{ $product->product_category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="status_cashback" @else for="status_cashback_"{{ $key }} @endif class="form-label">Status</label><span style="color: red;">*</span>
                                                <select @if($key == 0) id="status_cashback" @else id="status_cashback_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="status_cashback[]" required>
                                                    <option>-- Pilih Status --</option>
                                                    <option @if($item->status_cashback == 'Sudah Cair') selected @endif value="Sudah Cair">Sudah Cair</option>
                                                    <option @if($item->status_cashback == 'Belum Cair') selected @endif value="Belum Cair">Belum Cair</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="bank_id_cashback" @else for="bank_id_cashback_"{{ $key }} @endif class="form-label">Dari Bank</label>
                                                <select @if($key == 0) id="bank_id_cashback" @else id="bank_id_cashback_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id_cashback[]">
                                                    <option>-- Pilih Bank --</option>
            
                                                    @foreach ($bank as $item2)                                           
                                                        <option @if($item->bank_id == $item2->id) selected @endif value="{{ $item2->id }}">{{ $item2->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            
                                            <div class="col-md-6 mb-6">
                                                <label for="select2Basic" class="form-label">Keterangan</label>
                                                <textarea id="note_cashback" rows="3" class="form-control" name="note_cashback[]">{{ $item->note }}</textarea>
                                            </div>
                                        </div> 
                                    @endforeach
                                @else 
                                    <div class="row cashback-row" style="margin-top: 10%;">
                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                <input id="nominal_cashback" type="text" class="form-control nominal_cashback" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_cashback[]" oninput="format_cashback(this)"/>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date_cashback" name="date_cashback[]"/>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="category_id_cashback" class="form-label">Kategori Item</label><span style="color: red;">*</span>
                                            <select id="category_id_cashback" class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id_cashback[]">
                                                <option value="">-- Pilih Kategori --</option>
        
                                                @foreach ($products as $item)
                                                    <option value="{{ $item->id }}">{{ $item->product_category }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="status_cashback" class="form-label">Status</label>
                                            <select id="status_cashback" class="select2 form-select form-select-lg" data-allow-clear="true" name="status_cashback[]">
                                                <option>-- Pilih Status --</option>
                                                <option value="Sudah Cair">Sudah Cair</option>
                                                <option value="Belum Cair">Belum Cair</option>
                                            </select>
                                        </div>
        
                                        <div class="col-md-2 mb-4">
                                            <label for="bank_id_cashback" class="form-label">Dari Bank</label>
                                            <select id="bank_id_cashback" class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id_cashback[]">
                                                <option>-- Pilih Bank --</option>
        
                                                @foreach ($bank as $item)                                           
                                                    <option value="{{ $item->id }}">{{ $item->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-6">
                                            <label for="select2Basic" class="form-label">Keterangan</label>
                                            <textarea id="note_cashback" rows="3" class="form-control" name="note_cashback[]"></textarea>
                                        </div>

                                    </div> 
                                @endif
                              
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Pajak & Biaya</h5>
                        </div>

                        <div class="card-body">
                            <button id="add_tax" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                <i class="ti ti-plus me-sm-1"></i>
                            </button>


                            <div id="rowsContainerTax">
                                @if (count($tax) > 0)
                                    @foreach ($tax as $key => $item)
                                        <div class="row tax-row" style="margin-top: 10%;">
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Nominal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="nominal_tax" type="text" class="form-control nominal_tax" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_tax[]" value="{{ number_format($item->nominal, 0) }}" oninput="format_tax(this)"/>
                                                </div>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" id="date_cashback" name="date_tax[]" value="{{ $item->date }}"/>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Nama Transaksi</label>
                                                <input type="text" class="form-control" id="name_tax" name="name_tax[]" value="{{ $item->transaction_name }}"/>
                                            </div>

                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Keterangan</label>
                                                <textarea id="note_tax" rows="1" class="form-control" name="note_tax[]">{{ $item->note }}</textarea>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="bank_id_tax" @else for="bank_id_tax_"{{ $key }} @endif class="form-label">Dari Bank</label>
                                                <select @if($key == 0) id="bank_id_tax" @else id="bank_id_tax_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id_tax[]">
                                                    <option>-- Pilih Bank --</option>
            
                                                    @foreach ($bank as $item2)                                           
                                                        <option @if($item->bank_id == $item2->id) selected @endif value="{{ $item2->id }}">{{ $item2->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                    @endforeach
                                @else 
                                    <div class="row tax-row" style="margin-top: 10%;">
                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                <input id="nominal_tax" type="text" class="form-control nominal_tax" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_tax[]" oninput="format_tax(this)"/>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date_tax" name="date_tax[]"/>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="select2Basic" class="form-label">Nama Transaksi</label>
                                            <input type="text" class="form-control" id="name_tax" name="name_tax[]"/>
                                        </div>

                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Keterangan</label>
                                            <textarea id="note_tax" rows="1" class="form-control" name="note_tax[]"></textarea>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="bank_id_tax" class="form-label">Dari Bank</label>
                                            <select id="bank_id_tax" class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id_tax[]">
                                                <option>-- Pilih Bank --</option>
        
                                                @foreach ($bank as $item)                                           
                                                    <option value="{{ $item->id }}">{{ $item->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.invoice.index') }}" type="button"
                        class="btn btn-default">
                        Kembali
                    </a>
                    <button style="margin-left: 3%;"  type="submit" class="btn btn-warning">
                        Perbarui
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- / Content -->
</div>
@endsection

@push('js')
    <script>
        function initializeSelect2(element) {
            $(element).select2({
                theme: 'default' // Ensure Select2 uses Bootstrap 4 theme
            });
        }

        let total_receivables = 0;

        function update_piutang()
        {
            total_receivables = 0;
            total_nominal = 0;            
            var receivables_price = $('#receivables').val().replace(/[^0-9]/g, '') || 0;
            // console.log(receivables_price);

            $('.piutang-row').each(function() {
                var nominal = $(this).find('.nominal').val().replace(/[^0-9]/g, '') || 0;
                // console.log('==========');
                // console.log(Number(receivables_price) - Number(nominal));

                // total_receivables = Number(receivables_price) - Number(nominal);
                total_nominal += Number(nominal);

                var formattedValue = formatCurrency($(this).find('.nominal').val().replace(/[^0-9]/g, ''));
                // console.log(formattedValue);
                $(this).find('.nominal').val(formattedValue);
            });

            total_receivables = Number(receivables_price) - Number(total_nominal);
     
            // console.log(total_nominal);
            $('#receivables_label').text("Total Piutang Rp. " + total_receivables.toLocaleString());
        }

        initializeSelect2('#bank_id');

        $('#add_payment_cust').click(function() {
            var clonedRow = $('.piutang-row:first').clone();
            clonedRow.find('input, textarea').val('');

            // console.log(clonedRow);
            clonedRow.find('.select2-container').remove();
            // clonedRow.find('select').removeAttr('data-select2-id').removeAttr('aria-hidden').removeClass('select2-hidden-accessible').removeClass('select2');

            // console.log(clonedRow);

            let param_id = [];

            clonedRow.find('select').each(function() {
                var newId = $(this).attr('id') + '_' + $('.piutang-row').length;
                // console.log(newId);
                $(this).attr('id', newId);
                $(this).addClass('select2');
                param_id.push(newId);
            });


            $('#rowsContainer').append(clonedRow);

            initializeSelect2('#' + clonedRow.find('#bank_id').attr('id'));

            param_id.forEach(element => {
                initializeSelect2('#' + clonedRow.find('#'+element).attr('id'));
            });


            clonedRow.append('<div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;"><button type="button" class="btn btn-danger removeRowBtn"><i class="ti ti-trash me-sm-1"></i></button></div>');

            clonedRow.find('.removeRowBtn').click(function() {
                $(this).closest('.piutang-row').remove();
            });
        });

        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.piutang-row').remove();
            update_piutang();
        });

        initializeSelect2('#bank_id_refund');
        initializeSelect2('#category_id_refund');

        $('#add_refund').click(function() {
            var clonedRow = $('.refund-row:first').clone();
            clonedRow.find('input, textarea').val('');

       
            clonedRow.find('.select2-container').remove();


            let param_id = [];

            clonedRow.find('select').each(function() {
                var newId = $(this).attr('id') + '_' + $('.refund-row').length;
                // console.log(newId);
                $(this).attr('id', newId);
                $(this).addClass('select2');
                param_id.push(newId);
            });


            $('#rowsContainerRefund').append(clonedRow);

            initializeSelect2('#' + clonedRow.find('#bank_id_refund').attr('id'));
            initializeSelect2('#' + clonedRow.find('#category_id_refund').attr('id'));

            param_id.forEach(element => {
                initializeSelect2('#' + clonedRow.find('#'+element).attr('id'));
            });


            clonedRow.append('<div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;"><button type="button" class="btn btn-danger removeRowBtnRefund"><i class="ti ti-trash me-sm-1"></i></button></div>');

            clonedRow.find('.removeRowBtnRefund').click(function() {
                $(this).closest('.refund-row').remove();
            });
        });

        $(document).on('click', '.removeRowBtnRefund', function() {
            $(this).closest('.refund-row').remove();
        });

        initializeSelect2('#bank_id_cashback');
        initializeSelect2('#category_id_cashback');

        $('#add_cashback').click(function() {
            var clonedRow = $('.cashback-row:first').clone();
            clonedRow.find('input, textarea').val('');

       
            clonedRow.find('.select2-container').remove();


            let param_id = [];

            clonedRow.find('select').each(function() {
                var newId = $(this).attr('id') + '_' + $('.cashback-row').length;
                // console.log(newId);
                $(this).attr('id', newId);
                $(this).addClass('select2');
                param_id.push(newId);
            });


            $('#rowsContainerCashback').append(clonedRow);

            initializeSelect2('#' + clonedRow.find('#bank_id_cashback').attr('id'));
            initializeSelect2('#' + clonedRow.find('#category_id_cashback').attr('id'));

            param_id.forEach(element => {
                initializeSelect2('#' + clonedRow.find('#'+element).attr('id'));
            });


            clonedRow.append('<div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;"><button type="button" class="btn btn-danger removeRowBtnCashback"><i class="ti ti-trash me-sm-1"></i></button></div>');

            clonedRow.find('.removeRowBtnCashback').click(function() {
                $(this).closest('.cashback-row').remove();
            });
        });

        $(document).on('click', '.removeRowBtnCashback', function() {
            $(this).closest('.cashback-row').remove();
        });

        initializeSelect2('#bank_id_tax');
        initializeSelect2('#category_id_tax');

        $('#add_tax').click(function() {
            var clonedRow = $('.tax-row:first').clone();
            clonedRow.find('input, textarea').val('');

       
            clonedRow.find('.select2-container').remove();


            let param_id = [];

            clonedRow.find('select').each(function() {
                var newId = $(this).attr('id') + '_' + $('.tax-row').length;
                // console.log(newId);
                $(this).attr('id', newId);
                $(this).addClass('select2');
                param_id.push(newId);
            });


            $('#rowsContainerTax').append(clonedRow);

            initializeSelect2('#' + clonedRow.find('#bank_id_tax').attr('id'));
            initializeSelect2('#' + clonedRow.find('#category_id_tax').attr('id'));

            param_id.forEach(element => {
                initializeSelect2('#' + clonedRow.find('#'+element).attr('id'));
            });


            clonedRow.append('<div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;"><button type="button" class="btn btn-danger removeRowBtnTax"><i class="ti ti-trash me-sm-1"></i></button></div>');

            clonedRow.find('.removeRowBtnTax').click(function() {
                $(this).closest('.tax-row').remove();
            });
        });

        $(document).on('click', '.removeRowBtnTax', function() {
            $(this).closest('.tax-row').remove();
        });

        function formatCurrency(value, prefix = "Rp. ") {
            // console.log(value);
            var number_string = value.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                remainder = split[0].length % 3,
                rupiah = split[0].substr(0, remainder),
                thousand = split[0].substr(remainder).match(/\d{3}/gi);

            if (thousand) {
                separator = remainder ? '.' : '';
                rupiah += separator + thousand.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function formatCurrency(value, prefix = "Rp. ") {
            // console.log(value);
            var number_string = value.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                remainder = split[0].length % 3,
                rupiah = split[0].substr(0, remainder),
                thousand = split[0].substr(remainder).match(/\d{3}/gi);

            if (thousand) {
                separator = remainder ? '.' : '';
                rupiah += separator + thousand.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function format_refund(inputElement)
        {
            var row = $(inputElement).closest('.refund-row');
            var nominal = row.find('.nominal_refund');

            var formattedValue = formatCurrency(nominal.val().replace(/[^0-9]/g, ''));
            nominal.val(formattedValue);
        }

        function format_cashback(inputElement)
        {
            var row = $(inputElement).closest('.cashback-row');
            var nominal = row.find('.nominal_cashback');

            var formattedValue = formatCurrency(nominal.val().replace(/[^0-9]/g, ''));
            nominal.val(formattedValue);
        }

        function format_tax(inputElement)
        {
            var row = $(inputElement).closest('.tax-row');
            var nominal = row.find('.nominal_tax');

            var formattedValue = formatCurrency(nominal.val().replace(/[^0-9]/g, ''));
            nominal.val(formattedValue);
        }
    </script>
@endpush
