$(function () {
    var table = $('#product-table'),
        dt_basic;

    if (table.length) {
        dt_basic = table.DataTable({
            ajax: "product/data",
            dataSrc: 'data',
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Incrementing number starting from 1
                    },
                    orderable: false
                },
                { data: 'product_category' },
                {
                    data: 'sale',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                {
                    data: 'purchase',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                {
                    data: 'profit',
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
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full, meta) {
                      return (
                        // '<div class="d-inline-block">' +
                        // '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>' +
                        // '<ul class="dropdown-menu dropdown-menu-end m-0">' +
                        //     '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
                        //     '<li><a href="javascript:;" class="dropdown-item">Archive</a></li>' +
                        //     '<div class="dropdown-divider"></div>' +
                        //     '<li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                        // '</ul>' +
                        // '</div>' +
                        '<a href="product/edit/'+ full.id + '" class="btn btn-sm btn-icon item-edit"><i class="text-primary ti ti-pencil"></i></a>'+
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
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                                    '<td>' + col.title + ':' + '</td> ' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            }
        });

        
        $('#product-table').on('click', '.item-delete', function () {
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
