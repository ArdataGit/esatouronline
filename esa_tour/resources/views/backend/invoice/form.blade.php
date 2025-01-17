@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $title }} /</span> {{ $action }}</h4>

            <form action="{{ route('backend.invoice.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-4">
                                        <label for="customer" class="form-label">Pelanggan</label><span style="color: red;">*</span>
                                        <button style="width: auto; padding: 5px 10px; font-size: 12px; float:right; margin-bottom: 2%;" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUser">
                                            Add (+)
                                        </button>
                                        <select id="customer_id" name="customer_id" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                                            <option>-- Pilih Pelanggan --</option>
        
                                            @foreach ($customers as $item)
                                                <option @isset($data) @if($data->customer_id == $item->id) selected  @endif @endisset value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            
                                    <div class="col-md-3 mb-4">
                                        <label for="select2Basic" class="form-label">Tanggal Penerbitan</label><span style="color: red;">*</span>
                                        <input type="date" name="date_publisher" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp" required @isset($data) value="{{ $data->date_publisher }}" @endisset/>
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label for="select2Basic" class="form-label">Tanggal Jatuh Tempo</label>
                                        <input type="date" name="due_date" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp" @isset($data) value="{{ $data->due_date }}" @endisset/>
                                    </div>
        
                                    <div class="col-md-3 mb-4">
                                        <label for="physical_invoice_id" class="form-label">Invoice Fisik</label><span style="color: red;">*</span>
                                        <select id="physical_invoice_id" class="select2 form-select form-select-lg" data-allow-clear="true" name="physical_invoice_id" required>
                                            <option>-- Pilih Invoice Fisik --</option>
        
                                            @foreach ($physical_invoie as $item)
                                                <option @isset($data) @if($data->physical_invoice_id == $item->id) selected  @endif @endisset value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="col-md-3 mb-4">
                                        <label for="select2Basic" class="form-label">Nomor Invoice</label><span style="color: red;">*</span>
                                        <input type="text" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp" name="invoice_number" required @isset($data) value="{{ $data->invoice_number }}" @endisset/>
                                    </div>

                                    <div class="col-md-3 mb-4">
                                        <label for="status" class="form-label">Status Invoice</label><span style="color: red;">*</span>
                                        <select id="status" class="select2 form-select form-select-lg" data-allow-clear="true" name="status" required>
                                            <option>-- Pilih Status --</option>
                                            <option @isset($data) @if($data->status == 'Aktif') selected  @endif @endisset value="Aktif">Aktif</option>
                                            <option @isset($data) @if($data->status == 'Void') selected  @endif @endisset value="Void">Void</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
        
                    <!-- File input -->
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <button id="addRowBtn" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                    <i class="ti ti-plus me-sm-1"></i> Tambah Item
                                </button>
                                
                                <div id="rowsContainer">
                                    <input type="hidden" name="invoice_id" @isset($data) value="{{ $data->id }}" @endisset>
                                    @if (isset($data_d))
                                        @foreach ($data_d as $key => $value)
                                            <div class="row item-row" style="margin-top: 10%;">
                                                <hr>
                                                <div class="col-md-2 mb-4">
                                                    <label @if($key == 0) for="category_id" @else for="category_id_"{{ $key }} @endif class="form-label">Kategori Item</label><span style="color: red;">*</span>
                                                    <select @if($key == 0) id="category_id" @else id="category_id_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id[]" required>
                                                        <option>-- Pilih Kategori --</option>
                
                                                        @foreach ($products as $item)
                                                            <option @if($value->category_id == $item->id) selected  @endif value="{{ $item->id }}">{{ $item->product_category }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-2 mb-4">
                                                    <label for="select2Basic" class="form-label">Nama Produk</label><span style="color: red;">*</span>
                                                    <input type="text" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp" name="product_name[]" required value="{{ $value->product_name }}"/>
                                                </div>
                
                                                <div class="col-md-1 mb-4">
                                                    <label for="select2Basic" class="form-label">Kuantiti</label><span style="color: red;">*</span>
                                                    <input id="quantityInput" type="number" min="0" class="form-control quantityInput" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="qty[]" value="{{ $value->qty }}" oninput="update_total(this)" required/>
                                                </div>
                
                                                <div class="col-md-2 mb-4">
                                                    <label for="select2Basic" class="form-label">Harga Jual</label><span style="color: red;">*</span>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                        <input id="sellingPriceInput" type="text" class="form-control sellingPriceInput" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="selling_price[]" value="{{ $value->selling_price }}" oninput="update_total(this)" required/>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-2 mb-4">
                                                    <label @if($key == 0) for="from_bank" @else for="from_bank_"{{ $key }} @endif class="form-label">Sumber Dana</label><span style="color: red;">*</span>
                                                    <select @if($key == 0) id="from_bank" @else id="from_bank_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="from_bank[]" required>
                                                        <option>-- Pilih Sumber --</option>

                                                        @foreach ($bank as $item)                                           
                                                            <option @if($value->from_bank == $item->id) selected @endif value="bank-{{ $item->id }}"  data-source="bank">{{ $item->bank_name }}</option>
                                                        @endforeach

                                                        @foreach ($deposit as $item)                                           
                                                            <option @if($value->deposit_id == $item->id) selected @endif value="deposit-{{ $item->id }}" data-source="deposit">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-2 mb-4">
                                                    <label for="select2Basic" class="form-label">Harga Beli</label><span style="color: red;">*</span>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                        <input id="purchase_price" value="{{ $value->purchase_price }}" oninput="update_total_purchase(this)" type="text" class="form-control purchase_price" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="purchase_price[]" required/>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-1 mb-4">
                                                    <label for="select2Basic" class="form-label">Jumlah</label>
                                                    <label id="priceLabel" for="select2Basic" class="form-label priceLabel">RP. {{ number_format($value->total_price_sell, 0) }}</label>
                                                    <input id="total_price_sell" name="total_price_sell[]" class="total_price_sell" type="hidden" value="{{ $value->total_price_sell }}">
                                                </div>
                
                                                <div class="col-md-6 mb-4">
                                                    <label for="select2Basic" class="form-label">Keterangan</label>
                                                    <textarea id="floatingInput" rows="4" class="form-control" name="note[]">{{ $value->note }}</textarea>
                                                </div>
                
                                                <div class="col-md-2 mb-4">
                                                    <label for="select2Basic" class="form-label">Hutang Ke Vendor</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                        <input
                                                        type="text"
                                                        class="form-control debt_to_vendors"
                                                        placeholder=""
                                                        aria-label=""
                                                        aria-describedby="basic-addon11" 
                                                        name="debt_to_vendors[]"
                                                        value="{{ $value->debt_to_vendors }}"
                                                        oninput="format_debt(this)"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else 
                                        <div class="row item-row" style="margin-top: 10%;">
                                            <hr>
                                            <div class="col-md-2 mb-4">
                                                <label for="category_id" class="form-label">Kategori Item</label><span style="color: red;">*</span>
                                                <select id="category_id" class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id[]" required>
                                                    <option>-- Pilih Kategori --</option>
            
                                                    @foreach ($products as $item)
                                                        <option value="{{ $item->id }}">{{ $item->product_category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                    
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Nama Produk</label><span style="color: red;">*</span>
                                                <input type="text" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp" name="product_name[]" required/>
                                            </div>
            
                                            <div class="col-md-1 mb-4">
                                                <label for="select2Basic" class="form-label">Kuantiti</label><span style="color: red;">*</span>
                                                <input id="quantityInput" type="number" min="0" class="form-control quantityInput" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="qty[]" oninput="update_total(this)" required/>
                                            </div>
            
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Harga Jual</label><span style="color: red;">*</span>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="sellingPriceInput" type="text" class="form-control sellingPriceInput" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="selling_price[]" oninput="update_total(this)" required/>
                                                </div>
                                            </div>
            
                                            <div class="col-md-2 mb-4">
                                                <label for="from_bank" class="form-label">Sumber Dana</label><span style="color: red;">*</span>
                                                <select id="from_bank" class="select2 form-select form-select-lg" data-allow-clear="true" name="from_bank[]" required>
                                                    <option>-- Pilih Sumber --</option>
            
                                                    @foreach ($bank as $item)                                           
                                                        <option value="bank-{{ $item->id }}" data-source="bank">{{ $item->bank_name }}</option>
                                                    @endforeach

                                                    @foreach ($deposit as $item)                                           
                                                        <option value="deposit-{{ $item->id }}" data-source="deposit">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Harga Beli</label><span style="color: red;">*</span>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="purchase_price" oninput="update_total_purchase(this)" type="text" class="form-control purchase_price" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="purchase_price[]" required/>
                                                </div>
                                            </div>
            
                                            <div class="col-md-1 mb-4">
                                                <label for="select2Basic" class="form-label">Jumlah</label>
                                                <label id="priceLabel" for="select2Basic" class="form-label priceLabel">RP. 0</label>
                                                <input id="total_price_sell" name="total_price_sell[]" class="total_price_sell" type="hidden">
                                            </div>
            
                                            <div class="col-md-6 mb-4">
                                                <label for="select2Basic" class="form-label">Keterangan</label>
                                                <textarea id="floatingInput" rows="4" class="form-control" name="note[]"></textarea>
                                            </div>
            
                                            <div class="col-md-2 mb-4">
                                                <label for="select2Basic" class="form-label">Hutang Ke Vendor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input
                                                    type="text"
                                                    class="form-control debt_to_vendors"
                                                    placeholder=""
                                                    aria-label=""
                                                    aria-describedby="basic-addon11" 
                                                    name="debt_to_vendors[]"
                                                    oninput="format_debt(this)"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">                            
                                <dl class="row mb-0">
                                    <hr>
                                    <dt class="col-6 fw-normal text-heading">Total Harga Jual</dt>
                                    <dd id="price_total_selling" class="col-6 text-end price_total_selling">
                                        @if (isset($data))
                                            Rp. {{ number_format($data->price_total_selling) }}
                                        @else 
                                            Rp. 0
                                        @endif
                                    </dd>
                                    <input id="price_selling" name="price_total_selling" type="hidden" @if (isset($data)) value="{{ $data->price_total_selling }}" @endif>

                                    <hr>
                                    <dt class="col-6 fw-normal text-heading">Total Modal</dt>
                                    <dd id="price_total_purchase" class="col-6 text-end price_total_purchase">
                                        @if (isset($data))
                                            Rp. {{ number_format($data->price_total_purchase) }}
                                        @else 
                                            Rp. 0
                                        @endif
                                    </dd>
                                    <input id="price_purchase" name="price_total_purchase" type="hidden" @if (isset($data)) value="{{ $data->price_total_purchase }}" @endif>
        
                                    <hr>
                                    <dt class="col-6 fw-normal text-heading">Total Keuntungan</dt>
                                    <dd id="total_profit" class="col-6 text-end total_profit">
                                        @if (isset($data))
                                            Rp. {{ number_format($data->total_profit) }}
                                        @else 
                                            Rp. 0
                                        @endif
                                    </dd>
                                    <input id="profit" name="total_profit" type="hidden" @if (isset($data)) value="{{ $data->total_profit }}" @endif>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-12">
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
                                                        <input id="nominal_refund" type="text" class="form-control nominal_refund" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_refund[]" value="{{ $item->nominal }}" oninput="format_refund(this)"/>
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
                                                            <option @if($item->product_id === $product->id) selected @endif value="{{ $product->id }}">{{ $product->product_category }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2 mb-4">
                                                    <label @if($key == 0) for="refund_category" @else for="refund_category_"{{ $key }} @endif class="form-label">Kategori Refund</label><span style="color: red;">*</span>
                                                    <select @if($key == 0) id="refund_category" @else id="refund_category_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="refund_category[]" required>
                                                        <option>-- Pilih Kategori --</option>
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
                                                <select id="category_id_refund" class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id_refund[]" required>
                                                    <option>-- Pilih Kategori --</option>
            
                                                    @foreach ($products as $item)
                                                        <option value="{{ $item->id }}">{{ $item->product_category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 mb-4">
                                                <label for="refund_category" class="form-label">Kategori Refund</label><span style="color: red;">*</span>
                                                <select id="refund_category" class="select2 form-select form-select-lg" data-allow-clear="true" name="refund_category[]" required>
                                                    <option>-- Pilih Kategori --</option>
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
                    </div> --}}
    
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
                                                        <input id="nominal_cashback" type="text" class="form-control nominal_cashback" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_cashback[]" value="{{ $item->nominal }}" oninput="format_cashback(this)"/>
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
                                                <select id="category_id_cashback" class="select2 form-select form-select-lg" data-allow-clear="true" name="category_id_cashback[]" required>
                                                    <option>-- Pilih Kategori --</option>
            
                                                    @foreach ($products as $item)
                                                        <option value="{{ $item->id }}">{{ $item->product_category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            
                                            <div class="col-md-2 mb-4">
                                                <label for="status_cashback" class="form-label">Status</label><span style="color: red;">*</span>
                                                <select id="status_cashback" class="select2 form-select form-select-lg" data-allow-clear="true" name="status_cashback[]" required>
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
                                                        <input id="nominal_tax" type="text" class="form-control nominal_tax" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal_tax[]" value="{{ $item->nominal }}" oninput="format_tax(this)"/>
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
                        <a href="{{ route('backend.invoice.index') }}" type="button" class="btn btn-default">
                            Kembali
                        </a>
        
                        <button style="margin-left: 3%;"  type="submit" class="btn btn-warning">
                            perbarui
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- / Content -->
        <!-- Edit User Modal -->
        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Tambah Pelanggan</h3>
                            {{-- <p class="text-muted">Updating user details will receive a privacy audit.</p> --}}
                        </div>
                        <form id="customer_form" class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="modalEditUserFirstName">Nama</label><span style="color: red;"> *</span>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ketik nama" required/>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="modalEditUserLastName">Kontak</label><span style="color: red;"> *</span>
                                <input type="text" id="contact" name="contact" class="form-control" placeholder="Ketik Kontak" required/>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="modalEditUserName">Email</label><span style="color: red;"> *</span>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Ketik Email" required/>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="province_id">Provinsi</label><span style="color: red;"> *</span>
                                <select id="province_id" name="province_id" class="select2 form-select" aria-label="Default select example" required>
                                    <option selected>-- Pilih Provinsi --</option>

                                    @foreach ($provinces as $item) 
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="city_id">Kota</label><span style="color: red;"> *</span>
                                <select id="city_id" name="city_id" class="select2 form-select" aria-label="Default select example" required>
                                    <option>-- Pilih Kota --</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="district_id">Kecamatan</label><span style="color: red;"> *</span>
                                <select id="district_id" name="district_id" class="select2 form-select" aria-label="Default select example" required>
                                    <option>-- Pilih Kecamatan --</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="post_code">Kode Pos</label>
                                <input type="text" id="post_code" name="post_code" class="form-control" placeholder="Ketik Kode Pos" />
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="address">Alamat</label>
                                <textarea id="address" rows="4" name="address" class="form-control"></textarea>
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
        <!--/ Edit User Modal -->
    </div>
@endsection

@push('js')
    <script>
        $('#province_id').change(function() {
            var province_id = $(this).val();

            if (province_id) {
                $('#loading').show();

                $.ajax({
                    url: 'get-city/' + province_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#city_id').empty().trigger('change');
                        $('#city_id').append('<option>-- Pilih City --</option>');

                        $.each(data, function(key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#city_id').trigger('change');
                        $('#loading').hide();
                    }
                });
            } else {
                $('#city_id').empty();
                $('#city_id').append('<option selected>-- Pilih City --</option>');
                $('#city_id').select2();
            }
        });

        $('#city_id').change(function() {
            var district_id = $(this).val();

            if (district_id) {
                $('#loading').show();

                $.ajax({
                    url: 'get-district/' + district_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#district_id').empty().trigger('change');
                        $('#district_id').append('<option>-- Pilih Kecamatan --</option>');

                        $.each(data, function(key, value) {
                            $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#district_id').trigger('change');
                        $('#loading').hide();
                    }
                });
            } else {
                $('#district_id').empty().trigger('change');
                $('#district_id').append('<option selected>-- Pilih City --</option>');
                $('#district_id').trigger('change');
            }

        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#customer_form').on('submit', function(event) {
            event.preventDefault();

            var formData = $(this).serialize();
            $('#loading').show();
          

            $.ajax({
                url: 'customer-store',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        $('#customer_id').empty().trigger('change');
                        $('#customer_id').append('<option>-- Pilih Pelanggan --</option>');

                        $.each(data.data, function(key, value) {
                            // console.log(value);
                            $('#customer_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#customer_id').trigger('change');

                        Swal.fire('Sukses !!', data.message, 'success');
                    }else{
                        Swal.fire('Gagal !!', data.message, 'error');
                    }
                   
                    $('#loading').hide();

                    $('#editUser').find('.btn-close').trigger('click');
                },
                error: function(xhr, status, error) {
                  
                }
            });
        });

        function initializeSelect2(element) {
            $(element).select2({
                theme: 'default' // Ensure Select2 uses Bootstrap 4 theme
            });
        }

        initializeSelect2('#category_id');
        initializeSelect2('#from_bank');

        $('#addRowBtn').click(function() {
            var clonedRow = $('.item-row:first').clone();
            clonedRow.find('input, textarea').val('');

            // console.log(clonedRow);
            clonedRow.find('.select2-container').remove();
            // clonedRow.find('select').removeAttr('data-select2-id').removeAttr('aria-hidden').removeClass('select2-hidden-accessible').removeClass('select2');

            // console.log(clonedRow);

            let param_id = [];

            clonedRow.find('select').each(function() {
                var newId = $(this).attr('id') + '_' + $('.item-row').length;
                $(this).attr('id', newId);
                $(this).addClass('select2');
                param_id.push(newId);
            });


            $('#rowsContainer').append(clonedRow);

            initializeSelect2('#' + clonedRow.find('#category_id').attr('id'));
            initializeSelect2('#' + clonedRow.find('#from_bank').attr('id'));

            param_id.forEach(element => {
                initializeSelect2('#' + clonedRow.find('#'+element).attr('id'));
            });
        

            clonedRow.append('<div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;"><button type="button" class="btn btn-danger removeRowBtn"><i class="ti ti-trash me-sm-1"></i></button></div>');

            clonedRow.find('.removeRowBtn').click(function() {
                $(this).closest('.item-row').remove();
            });
        });

        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.item-row').remove();
            update_total_selling();
            update_total_purchase();
        });


        let total_selling = 0;
        let total_purchase = 0;
        let total = 0;

        function update_total(inputElement) {
            var row = $(inputElement).closest('.item-row');
            
            var quantityInput = row.find('.quantityInput');
            var sellingPriceInput = row.find('.sellingPriceInput');
            var priceLabel = row.find('.priceLabel');

            // var price_total_selling = document.getElementById("price_total_selling");

            // Get the values from the inputs
            var quantity = quantityInput.val() || 0;

            var pricePerUnit = sellingPriceInput.val().replace(/[^0-9]/g, '') || 0;

            quantity = Number(quantity);
            pricePerUnit = Number(pricePerUnit);

            total = quantity * pricePerUnit;

            priceLabel.text("RP. " + total.toLocaleString());
            var total_price_sell = row.find('.total_price_sell');
            total_price_sell.val(total);

            update_total_selling();
            update_total_purchase();
            var formattedValue = formatCurrency(sellingPriceInput.val());

            sellingPriceInput.val(formattedValue);
        }

        function update_total_purchase(){
            total_purchase = 0;

            $('.item-row').each(function() {
                var quantityInput = $(this).find('.quantityInput');
                var quantity = Number(quantityInput.val() || 0);
                var purchase_price = $(this).find('.purchase_price');
                var pricePerUnit = Number(purchase_price.val().replace(/[^0-9]/g, '') || 0);
                total_purchase += quantity * pricePerUnit;

                var formattedValue = formatCurrency(purchase_price.val());
                purchase_price.val(formattedValue);
            });

            var priceTotalPurchase = document.getElementById("price_total_purchase");
            if (priceTotalPurchase) {
                priceTotalPurchase.textContent = "Rp. " + total_purchase.toLocaleString();
            }

            $('#price_purchase').val(total_purchase);
            

            update_total_profit();

          
        }

        function update_total_selling() {
            total_selling = 0;

            $('.item-row').each(function() {
                var quantityInput = $(this).find('.quantityInput');
                var sellingPriceInput = $(this).find('.sellingPriceInput');
                var quantity = Number(quantityInput.val() || 0);
                var pricePerUnit = Number(sellingPriceInput.val().replace(/[^0-9]/g, '') || 0);
                total_selling += quantity * pricePerUnit;
            });

            var priceTotalSellingElement = document.getElementById("price_total_selling");
            if (priceTotalSellingElement) {
                priceTotalSellingElement.textContent = "Rp. " + total_selling.toLocaleString();
            }

            $('#price_selling').val(total_selling);

            update_total_profit();
        }

        function update_total_profit(){
            var total_profit = total_selling - total_purchase;
            var priceTotalProfit = document.getElementById("total_profit");

            if (priceTotalProfit) {
                priceTotalProfit.textContent = "Rp. " + total_profit.toLocaleString();
            }

            $('#profit').val(total_profit);
        }

        initializeSelect2('#bank_id_refund');
        initializeSelect2('#category_id_refund');
        initializeSelect2('#refund_category');
        

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
            initializeSelect2('#' + clonedRow.find('#refund_category').attr('id'));

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

        function format_debt(inputElement)
        {
            var row = $(inputElement).closest('.item-row');
            var nominal = row.find('.debt_to_vendors');
            // console.log(nominal.val());

            var formattedValue = formatCurrency(nominal.val());
            nominal.val(formattedValue);
        }

        function format_refund(inputElement)
        {
            var row = $(inputElement).closest('.refund-row');
            var nominal = row.find('.nominal_refund');

            var formattedValue = formatCurrency(nominal.val());
            nominal.val(formattedValue);
        }

        function format_cashback(inputElement)
        {
            var row = $(inputElement).closest('.cashback-row');
            var nominal = row.find('.nominal_cashback');

            var formattedValue = formatCurrency(nominal.val());
            nominal.val(formattedValue);
        }

        function format_tax(inputElement)
        {
            var row = $(inputElement).closest('.tax-row');
            var nominal = row.find('.nominal_tax');

            var formattedValue = formatCurrency(nominal.val());
            nominal.val(formattedValue);
        }
    </script>
@endpush