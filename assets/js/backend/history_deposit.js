$(function () {
    var table = $('#history-table'),
        dt_basic;

    if (table.length) {
        dt_basic = table.DataTable({
            ajax: {
                url: 'data',
                data: function(d) {
                    d.date = $('#date').val();
                    d.deposit_id = $('#deposit_id').val();
                }
            },
            dataSrc: 'data',
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Incrementing number starting from 1
                    },
                    orderable: false
                },
                { data: 'transaction_name' },
                {
                    data: null,
                    render: function (data, type, row) {
                       if (data['type'] == 'deposit' || data['type'] == 'transfer_income') {
                            return 'Pemasukan';
                       }else if(data['type'] == 'expense' || data['type'] == 'transfer_expense'){
                            return  'Pengeluaran';
                       }else if(data['type'] == 'vendor_payment'){
                            return 'Pengeluaran';
                       }else{
                            return '-';
                       }
                    }
                },
                {
                    data: 'nominal',
                    render: function (data, type, row) {
                        return 'Rp.' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    }
                },
                { data: 'date' },
            ],
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 2 },
                { responsivePriority: 3, targets: 3 },
            ],
            // order: [[1, 'desc']],
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

    }

    $('#filter-button').on('click', function() {
        // console.log('masok');
        dt_basic.ajax.reload();
    });
});
