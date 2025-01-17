@extends('layouts.backend.app')
@section('bank', 'active')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

        <div class="row">
            <div class="col-md-3 mb-4">
                <label for="select2Basic" class="form-label">Tanggal</label><span style="color: red;">*</span>
                <input id="date" type="date" name="date" class="form-control" id="floatingInput"
                    aria-describedby="floatingInputHelp" required @isset($data) value="{{ $data->date_publisher }}"
                    @endisset />
            </div>

            <div class="col-md-3 mb-4">
                <button id="filter-button" style="margin-top: 10%;" type="submit" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                </button>
            </div>
        </div>
        <!-- DataTable with Buttons -->
        <div class="card">
            <input type="hidden" id="bank_id" value="{{ $id }}">

            <div class="card-datatable table-responsive pt-0">
                <table id="bank-table" class="datatables-basic table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Transaksi</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/backend/history_bank.js') }}"></script>
    <script>
        // var id = '{{ route("backend.bank.destroy", ":id") }}';

    </script>
@endpush
