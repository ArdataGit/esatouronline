<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class SaleExport implements FromView
{
    protected $start_date, $end_date;

 
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function view(): View
    {
        $invoice = Invoice::select('invoice.date_publisher', DB::raw('SUM(invoice_d.selling_price * qty) as price'), 'products.product_category')
                ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                ->join('products', 'invoice_d.category_id', 'products.id')
                ->where('status', 'Aktif')
                ->when($this->start_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '>=', $date);
                    
                })->when($this->end_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '<=', $date);
                });


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

        return view('backend.sale.export', [
            'invoice'        => $invoice, 
        ]);

    }
}
