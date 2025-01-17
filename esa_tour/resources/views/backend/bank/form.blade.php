@extends('layouts.backend.app')
@section('bank', 'active')
@section('content')
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $title }} /</span> {{ $action }}</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <form action="{{ route('backend.bank.store') }}" method="POST"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf

                                <input type="hidden" id="bank_id" name="bank_id" @isset($data) value="{{ $data->id }}"
                                    @endisset>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="account_id">Akun</label><span style="color: red;">
                                        *</span>
                                    <select id="account_id" name="account_id" class="select2 form-select"
                                        aria-label="Default select example" required>
                                        <option>-- Pilih Akun --</option>

                                        @foreach($accounts as $item)
                                            <option @isset($data) @if($item->id == $data->account_id) selected @endif @endisset value="{{ $item->id }}" >{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserFirstName">Nama Rekening</label><span
                                        style="color: red;"> *</span>
                                    <input type="text" id="account_name" name="account_name" class="form-control"
                                        placeholder="Ketik Nama Rekening" required @isset($data)
                                        value="{{ $data->account_name }}" @endisset />
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserFirstName">Nama Bank</label><span
                                        style="color: red;"> *</span>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control"
                                        placeholder="Ketik Nama Bank" required @isset($data)
                                        value="{{ $data->bank_name }}" @endisset />
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserFirstName">Nomor Rekening</label><span
                                        style="color: red;"> *</span>
                                    <input type="text" id="account_number" name="account_number" class="form-control"
                                        placeholder="Ketik Nomor Rekening" required @isset($data)
                                        value="{{ $data->account_number }}" @endisset />
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="select2Basic" class="form-label">Saldo Awal</label><span
                                        style="color: red;"> *</span>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                        <input id="beginning_balance" type="text" class="form-control" placeholder="" aria-label=""
                                            aria-describedby="basic-addon11" name="beginning_balance" required
                                            @isset($data) value="{{ $data->beginning_balance }}" @endisset />
                                    </div>
                                </div>



                                <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 5%;">
                                    <a href="{{ route('backend.bank.index') }}"
                                        class="btn btn-label-secondary">
                                        Batal
                                    </a>

                                    <button type="submit" class="btn btn-warning me-sm-3 me-1">Buat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#province_id').change(function () {
                var province_id = $(this).val();
                var getCityUrl =
                    '{{ route("backend.customer.get_city", ":province_id") }}';
                var url = getCityUrl.replace(':province_id', province_id);
                var customer_id = $('#customer_id').val();
                url += '?customer_id=' + customer_id;

                if (province_id) {
                    $('#loading').show();

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#city_id').empty().trigger('change');
                            $('#city_id').append('<option>-- Pilih City --</option>');

                            $.each(data.data, function (key, value) {
                                if (data.selected_city !== 0) {
                                    if (data.selected_city == value.id) {
                                        $('#city_id').append('<option value="' +
                                            value.id + '" selected>' + value
                                            .name + '</option>');
                                    } else {
                                        $('#city_id').append('<option value="' +
                                            value.id + '">' + value.name +
                                            '</option>');
                                    }
                                } else {
                                    $('#city_id').append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                }
                            });

                            $('#city_id').trigger('change');
                            if (data.selected_city == 0) {}

                            $('#loading').hide();
                        }
                    });
                } else {
                    $('#city_id').empty();
                    $('#city_id').append('<option selected>-- Pilih City --</option>');
                    $('#city_id').select2();
                }
            });

            $('#city_id').change(function () {
                var district_id = $(this).val();
                var getDistrictUrl =
                    '{{ route("backend.customer.get_district", ":district_id") }}';
                var url = getDistrictUrl.replace(':district_id', district_id);
                var customer_id = $('#customer_id').val();
                url += '?customer_id=' + customer_id;

                if (district_id) {
                    $('#loading').show();

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#district_id').empty().trigger('change');
                            $('#district_id').append(
                                '<option>-- Pilih Kecamatan --</option>');

                            $.each(data.data, function (key, value) {
                                if (data.selected_district !== 0) {
                                    if (data.selected_district == value.id) {
                                        $('#district_id').append('<option value="' +
                                            value.id + '" selected>' + value
                                            .name + '</option>');
                                    } else {
                                        $('#district_id').append('<option value="' +
                                            value.id + '">' + value.name +
                                            '</option>');
                                    }
                                } else {
                                    $('#district_id').append('<option value="' +
                                        value.id + '">' + value.name +
                                        '</option>');
                                }
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

            if ($('#province_id').val()) {
                $('#province_id').trigger('change');
            }

            function formatCurrency(value, prefix = "Rp. ") {
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

            $('#beginning_balance').on('keyup', function() {
                var formattedValue = formatCurrency(this.value);
                $(this).val(formattedValue);
            });
        });

    </script>
@endpush
