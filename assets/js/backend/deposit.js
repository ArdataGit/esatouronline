$(function () {
    var table = $('#deposit-table'),
        dt_basic;

    if (table.length) {
        dt_basic = table.DataTable({
            ajax: "deposit/data",
            dataSrc: 'data',
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Incrementing number starting from 1
                    },
                    orderable: false
                },
                { data: 'date' },
                // {
                //     data: 'bank',
                //     render: function (data, type, row) {
                //         return data['bank_name'];
                //     }
                // },
                { data: 'name' },
                {
                    data: 'beginning_balance',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                {
                    data: 'income',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                {
                    data: 'expense',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                {
                    data: 'balance',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                { data: null, defaultContent: '' }
            ],
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 2 },
                { responsivePriority: 3, targets: 3 },
                { responsivePriority: 4, targets: 4 },
                {
                    // Actions
                    targets: 7,
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full, meta) {
                      return (
                        '<a href="deposit/history/'+ full.id + '" class="btn btn-sm btn-icon item-edit"><i class="text-primary ti ti-eye"></i></a>'+
                        '<a href="deposit/edit/'+ full.id + '" class="btn btn-sm btn-icon item-edit"><i class="text-primary ti ti-pencil"></i></a>'+
                        '<a href="javascript:;" class="btn btn-sm btn-icon item-delete"><i class="text-primary ti ti-trash"></i></a>'
                      );
                    }
                  }
            ],
            order: [[1, 'desc']],
            dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            buttons: [],
        });

        
        $('#deposit-table').on('click', '.item-delete', function () {
            var row = $(this).closest('tr');
            var data = dt_basic.row(row).data();
            // console.log(data.id);

            Swal.fire({
                title: 'Are you sure?',
                text: "Apakah Anda Yakin Untuk Menghapusnya",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loading').show();

                    var id = data.id;
                    var url = deleteUrl.replace(':id', id);
                    // console.log(url);
                    // Perform the delete operation (e.g., send an AJAX request to the server)
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
            
                    
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function (result) {
                            // On success, remove the row from the table
                            dt_basic.row(row).remove().draw();
                            Swal.fire(
                                'Deleted!',
                                'Data Berhasil Dihapus.',
                                'success'
                            );

                            $('#loading').hide();
                        },
                        error: function (xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'Terjadi Kesalahan Pada Server, Coba Lagi Kembali.',
                                'error'
                            );

                            $('#loading').hide();
                        }
                    });
                }
            });
        });
    }
});
