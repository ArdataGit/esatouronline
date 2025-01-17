<?php

namespace App\Http\Controllers\Backend;

use App\Exports\SaleExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Penjualan';
    }

    public function index(Request $request)
    {
        // dd($request);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $title      = $this->title;
        $invoice = Invoice::select('invoice.date_publisher', DB::raw('SUM(invoice_d.selling_price * qty) as price'), 'products.product_category')
                ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                ->join('products', 'invoice_d.category_id', 'products.id')
                ->where('status', 'Aktif')
                ->when($request->start_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '>=', $date);
                    
                })->when($request->end_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '<=', $date);
                });

        // if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
        //     $invoice->where('invoice.date_publisher', Carbon::now());
        // };

        $invoice = $invoice->groupBy('invoice.date_publisher', 'products.product_category')->orderBy('invoice.created_at', 'desc')->get();

        $groupedInvoices = [];

        foreach ($invoice as $invoice) {
            $date = $invoice->date_publisher;
        
            if (!isset($groupedInvoices[$date])) {
                $groupedInvoices[$date] = [
                    'date_publisher' => $date,
                    'categories' => []
                ];
            }
        
            // Add the product category and its price to the categories array
            $groupedInvoices[$date]['categories'][] = [
                'product_category' => $invoice->product_category,
                'price' => $invoice->price
            ];
        }
        
        // Convert to a simple array if needed
        $groupedInvoices = array_values($groupedInvoices);
        $invoice = $groupedInvoices;
        // dd($invoice);

        return view('backend.sale.index', compact('title', 'invoice', 'start_date', 'end_date'));
    }

    public function export(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return Excel::download(new SaleExport($start_date, $end_date), 'penjualan.xlsx');
    }
}
