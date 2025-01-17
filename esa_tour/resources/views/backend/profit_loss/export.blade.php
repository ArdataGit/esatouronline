<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
       .container {
            display: flex;
            justify-content: space-between; /* Align tables to the left and right of the row */
        }
        .table-container {
            width: 48%; /* Adjust width as needed */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black; /* Add borders */
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h3>Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th>Ketegori</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total_sale = 0; @endphp
            @foreach ($invoice as $item)                                       
                <tr>
                    <td style="text-align: center;">{{ $item->product_category }}</td>
                    <td style="text-align: center;">Rp. {{ number_format($item->selling_price, 2) }}</td>

                    @php $total_sale += $item->selling_price; @endphp
                </tr>
            @endforeach

            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center;">Rp. {{ number_format($total_sale, 2) }}</td>
            </tr>

            <tr>
                <td style="text-align: center;">Retur Penjualan Tiket</td>
                <td style="text-align: center; color:red;">-Rp. @if($retur_sale) {{ number_format($retur_sale->price, 2) }} @else 0 @endif</td>
            </tr>

            <tr>
                <td style="text-align: center;">PPN</td>
                <td style="text-align: center; color:red;">-Rp. @if($ppn) {{ number_format($ppn->ppn, 2) }} @else 0 @endif</td>
            </tr>

            @php 
                $nominal_ppn = 0;
                $nominal_retur_sale = 0;

                if ($ppn) {
                    $nominal_ppn = $ppn->ppn;
                }

                if ($retur_sale) {
                    $nominal_retur_sale = $retur_sale->price;
                }

                $final_sale = $total_sale - $nominal_ppn - $nominal_retur_sale;
            
            @endphp

            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center; color: green;">Rp. {{ number_format($final_sale, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <h3>Harga Pokok Pembelian</h3>
    <table>
        <thead>
            <tr>
                <th>Ketegori</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total_purchase = 0; @endphp
            @foreach ($invoice as $item)                                       
                <tr>
                    <td style="text-align: center;">{{ $item->product_category }}</td>
                    <td style="text-align: center;">Rp. {{ number_format($item->purchase_price, 2) }}</td>

                    @php $total_purchase += $item->purchase_price; @endphp
                </tr>
            @endforeach

            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center;">Rp. {{ number_format($total_purchase, 2) }}</td>
            </tr>

            <tr>
                <td style="text-align: center;">Retur Pembelian Tiket</td>
                <td style="text-align: center; color:red;">-Rp. @if($retur_purchase) {{ number_format($retur_purchase->price, 2) }} @else 0 @endif</td>
            </tr>

            @php 
                $nominal_retur_purchase = 0;

                if ($retur_purchase) {
                    $nominal_retur_purchase = $retur_purchase->price;
                }

                $final_purchase = $total_purchase - $nominal_retur_purchase;     
            @endphp
            
            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center; color: green;">Rp. {{ number_format($final_purchase, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <h3>Laba Kotor</h3>
    <table>
        {{-- <thead>
            <tr>
                <th>Ketegori</th>
                <th>Nominal</th>
            </tr>
        </thead> --}}
        <tbody>
            <tr>
                <td style="text-align: center;">Penjualan</td>
                <td style="text-align: center;">Rp. {{ number_format($final_sale, 2) }}</td>
            </tr>

            <tr>
                <td style="text-align: center;">Harga Pokok Pembelian</td>
                <td style="text-align: center; color:red;">-Rp. {{ number_format($final_purchase, 2) }}</td>
            </tr>

            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center; color: green;">Rp. {{ number_format($final_sale - $final_purchase, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <h3>Biaya Operasional</h3>
    <table>
        <thead>
            <tr>
                <th>Ketegori</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total_expense = 0; @endphp

            @foreach ($expense as $item)                                       
                <tr>
                    <td style="text-align: center;">{{ $item->name }}</td>
                    <td style="text-align: center;">Rp. {{ number_format($item->nominal, 2) }}</td>

                    @php $total_expense += $item->nominal; @endphp
                </tr>
            @endforeach

            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center;">Rp. {{ number_format($total_expense, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <h3>Laba Bersih Perusahaan</h3>
    <table>
        <thead>
            <tr>
                <th>Ketegori</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">Harga Pokok Pembelian</td>
                <td style="text-align: center;">Rp. {{ number_format($final_purchase, 2) }}</td>
            </tr>

            <tr>
                <td style="text-align: center;">Biaya Operasional</td>
                <td style="text-align: center; color:red;">-Rp. {{ number_format($total_expense, 2) }}</td>
            </tr>

            <tr style="background-color: rgb(233, 232, 232);">
                <td style="text-align: center;">Total</td>
                <td style="text-align: center; color: green;">Rp. {{ number_format($final_purchase - $total_expense, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>