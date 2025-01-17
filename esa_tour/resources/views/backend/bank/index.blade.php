@extends('layouts.backend.app')
@section('bank', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <!-- DataTable with Buttons -->
            <div class="card">
                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="#" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#transfer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-transfer-down">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 3v6" />
                            <path d="M10 18l-3 3l-3 -3" />
                            <path d="M7 21v-18" />
                            <path d="M20 6l-3 -3l-3 3" />
                            <path d="M17 21v-2" />
                            <path d="M17 15v-2" />
                        </svg>
                        Transfer
                    </a>

                    <a style="margin-left: 3%;" href="{{ route('backend.bank.create') }}" type="button"
                        class="btn btn-primary">
                        <i class="ti ti-plus me-sm-1"></i>Tambah
                    </a>
                </div>

                <div class="card-datatable table-responsive pt-0">
                    <table id="bank-table" class="datatables-basic table table-sm">
                        <thead>
                            <tr>
                                <th style="font-size: 12px; padding: 4px;">No</th>
                                <th style="font-size: 12px; padding: 4px;">Nama Bank</th>
                                <th style="font-size: 12px; padding: 4px;">Saldo Awal</th>
                                <th style="font-size: 12px; padding: 4px;">Pemasukan</th>
                                <th style="font-size: 12px; padding: 4px;">Pengeluaran</th>
                                <th style="font-size: 12px; padding: 4px;">Saldo</th>
                                <th style="font-size: 12px; padding: 4px;">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>


            <!-- Edit User Modal -->
            <div class="modal fade" id="transfer" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2">Transfer Bank</h3>
                            </div>
                            <form id="transfer_form" class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="province_id">Dari Bank</label><span style="color: red;">
                                        *</span>
                                    <select id="from_bank" name="from_bank" class="select2 form-select"
                                        aria-label="Default select example" required>
                                        <option selected>-- Pilih Bank --</option>

                                        @foreach ($banks as $item)
                                            <option value="bank-{{ $item->id }}" data-source="bank">
                                                {{ $item->bank_name }}</option>
                                        @endforeach

                                        @foreach ($deposit as $item)
                                            <option value="deposit-{{ $item->id }}" data-source="deposit">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label" for="city_id">Ke Bank</label><span style="color: red;">
                                        *</span>
                                    <select id="to_bank" name="to_bank" class="select2 form-select"
                                        aria-label="Default select example" required>
                                        <option>-- Pilih Bank --</option>

                                        @foreach ($banks as $item)
                                            <option value="bank-{{ $item->id }}" data-source="bank">
                                                {{ $item->bank_name }}</option>
                                        @endforeach

                                        @foreach ($deposit as $item)
                                            <option value="deposit-{{ $item->id }}" data-source="deposit">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="select2Basic" class="form-label">Nominal</label><span
                                        style="color: red;">*</span>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                        <input id="nominal" type="text" class="form-control" placeholder=""
                                            aria-label="" aria-describedby="basic-addon11" name="nominal"
                                            @isset($data) value="{{ $data->beginning_balance }}" @endisset
                                            required />
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="transfer_date" class="form-label">Tanggal Transfer</label><span
                                        style="color: red;">*</span>
                                    <input id="transfer_date" type="date" class="form-control" name="date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                                </div>


                                <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 5%;">
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        Batal
                                    </button>

                                    <button id="submit_customer" type="submit"
                                        class="btn btn-warning me-sm-3 me-1">Buat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Edit User Modal -->
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/backend/bank.js') }}"></script>
    <script>
        var deleteUrl = '{{ route('backend.bank.destroy', ':id') }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endpush
