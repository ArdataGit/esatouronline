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
                <th>Tanggal</th>
                <th>Kategori Produk</th>
                <th>Nominal</th>
            </tr>

            @foreach ($invoice as $item)
                <tr>
                    <td>
                        {{ $item['date_publisher'] }}
                    </td>
                    <td>
                        {{ $item['categories'][0]['product_category'] }}
                    </td>
                    <td>
                        {{number_format($item['categories'][0]['price'], 2)  }} 
                    </td>
                </tr>

                @if(count($item['categories']) > 1)
                    @for ($i = 1; $i < count($item['categories']); $i++)
                        <tr>
                            <td></td>
                            <td>{{  $item['categories'][$i]['product_category'] }}</td>
                            <td>{{ number_format($item['categories'][$i]['price'], 2) }}</td>
                        </tr>
                    @endfor
                @endif       
            @endforeach
        </thead>
    </table>
</body>
</html>