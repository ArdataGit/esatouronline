<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitLossExport implements FromView, WithStyles, WithHeadings
{
    protected $periode, $start_date, $end_date;

    // Constructor to accept parameters
    public function __construct($periode, $start_date, $end_date)
    {
        // dd($periode);
        $this->periode = $periode;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    
    public function view(): View
    {
        $invoice   = Invoice::select(DB::raw('SUM(invoice_d.selling_price * invoice_d.qty) as selling_price'), 'products.product_category', DB::raw('SUM(invoice_d.purchase_price * invoice_d.qty) as purchase_price'))
                ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                ->join('products', 'invoice_d.category_id', 'products.id')
                ->where('invoice.status', 'Aktif')
                ->when($this->periode, function ($query, $periode) {
                    if ($periode == 'last_1_month') {
                        $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                        $today = Carbon::now()->endOfDay();
                        
                        $query->whereBetween('invoice.date_publisher', [$oneMonthAgo, $today]);
                    }else{
                        // dd('masok');
                        $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                        $today = Carbon::now()->endOfDay();

                        $query->whereBetween('invoice.date_publisher', [$oneYearAgo, $today]);
                    }
                  
                    
                })->when($this->start_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '>=', $date);
                    
                })->when($this->end_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '<=', $date);
                });


        if ($this->start_date == null && $this->periode == null && $this->end_date == null) {
            $invoice->where('invoice.date_publisher', Carbon::now());
        };

        $invoice = $invoice->groupBy('products.product_category')->get();

        $retur_sale = Invoice::select(DB::raw('SUM(bank_history.nominal) as price'))
                    ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
                    ->where('invoice.status', 'Aktif')
                    ->where('bank_history.refund_category', 'Refund Customer')
                    ->when($this->periode, function ($query, $periode) {
                        if ($periode == 'last_1_month') {
                            $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                            $today = Carbon::now()->endOfDay();
                            
                            $query->whereBetween('invoice.date_publisher', [$oneMonthAgo, $today]);
                        }else{
                            // dd('masok');
                            $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                            $today = Carbon::now()->endOfDay();
    
                            $query->whereBetween('invoice.date_publisher', [$oneYearAgo, $today]);
                        }
                    })
                    ->when($this->start_date, function ($query, $date) {
                        $query->where('invoice.date_publisher', '>=', $date);
                        
                    })->when($this->end_date, function ($query, $date) {
                        $query->where('invoice.date_publisher', '<=', $date);
                    });
                    // ->first();

        if ($this->start_date == null && $this->periode == null && $this->end_date == null) {
            $retur_sale->where('invoice.date_publisher', Carbon::now());
        };

        $retur_sale = $retur_sale->first();

        $retur_purchase = Invoice::select(DB::raw('SUM(bank_history.nominal) as price'))
            ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
            ->where('invoice.status', 'Aktif')
            ->where('bank_history.refund_category', 'Refund Supplier')
            ->when($this->periode, function ($query, $periode) {
                if ($periode == 'last_1_month') {
                    $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                    $today = Carbon::now()->endOfDay();
                    
                    $query->whereBetween('invoice.date_publisher', [$oneMonthAgo, $today]);
                }else{
                    // dd('masok');
                    $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                    $today = Carbon::now()->endOfDay();

                    $query->whereBetween('invoice.date_publisher', [$oneYearAgo, $today]);
                }
            })
            ->when($this->start_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '>=', $date);
                
            })->when($this->end_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '<=', $date);
            });
            // ->first();

        if ($this->start_date == null && $this->periode == null && $this->end_date == null) {
            $retur_purchase->where('invoice.date_publisher', Carbon::now());
        };

        $retur_purchase = $retur_purchase->first();

        $ppn = Invoice::select(DB::raw('SUM(bank_history.nominal) as ppn'))
            ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
            ->where('bank_history.type', 'tax')
            ->when($this->periode, function ($query, $periode) {
                if ($periode == 'last_1_month') {
                    $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                    $today = Carbon::now()->endOfDay();
                    
                    $query->whereBetween('invoice.date_publisher', [$oneMonthAgo, $today]);
                }else{
                    // dd('masok');
                    $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                    $today = Carbon::now()->endOfDay();

                    $query->whereBetween('invoice.date_publisher', [$oneYearAgo, $today]);
                }
            })
            ->when($this->start_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '>=', $date);
                
            })->when($this->end_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '<=', $date);
            });
            // ->first();
     
        if ($this->start_date == null && $this->periode == null && $this->end_date == null) {
            $ppn->where('invoice.date_publisher', Carbon::now());
        };

        $ppn = $ppn->first();

        
        $expense = Expense::when($this->start_date, function ($query, $date) {
            $query->where('date', '>=', $date);
            
        })
        ->when($this->periode, function ($query, $periode) {
            if ($periode == 'last_1_month') {
                $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                $today = Carbon::now()->endOfDay();
                
                $query->whereBetween('date', [$oneMonthAgo, $today]);
            }else{
                // dd('masok');
                $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                $today = Carbon::now()->endOfDay();

                $query->whereBetween('date', [$oneYearAgo, $today]);
            }
        })
        ->when($this->end_date, function ($query, $date) {
            $query->where('date', '<=', $date);
        });

        if ($this->start_date == null && $this->periode == null && $this->end_date == null) {
            $expense->where('date', Carbon::now());
        };

        $expense = $expense->get();

        // Pass the data to the Blade view
        return view('backend.profit_loss.export', [
           'invoice'        => $invoice, 
           'ppn'            => $ppn, 
           'retur_sale'     => $retur_sale, 
           'retur_purchase' => $retur_purchase, 
           'expense'        => $expense, 
           'start_date'     => $this->start_date, 
           'end_date'       => $this->end_date, 
           'periode'        => $this->periode
        ]);
    }

    public function headings(): array
    {
        return [
            ['Header 1', 'Header 2', 'Header 3'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => '260301'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '260301'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFC000'],
            ],
        ];

        // $sheet->getStyle('A1:B1')->applyFromArray($headerStyle);

        return [
            // Set styles for columns or rows here
        ];
    }
}
