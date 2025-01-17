<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfitLossExport;

class ProfitLossController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Laba Rugi';
    }

    public function index(Request $request)
    {
        // dd($request);
        $periode = $request->periode;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $title      = $this->title;
        $invoice   = Invoice::select(DB::raw('SUM(invoice_d.selling_price * invoice_d.qty) as selling_price'), 'products.product_category', DB::raw('SUM(invoice_d.purchase_price * invoice_d.qty) as purchase_price'))
                ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                ->join('products', 'invoice_d.category_id', 'products.id')
                ->where('invoice.status', 'Aktif')
                ->when($request->periode, function ($query, $periode) {
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
                  
                    
                })->when($request->start_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '>=', $date);
                    
                })->when($request->end_date, function ($query, $date) {
                    $query->where('invoice.date_publisher', '<=', $date);
                });


        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $invoice->where('invoice.date_publisher', Carbon::now());
        };

        $invoice = $invoice->groupBy('products.product_category')->get();

        $retur_sale = Invoice::select(DB::raw('SUM(bank_history.nominal) as price'))
                    ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
                    ->where('invoice.status', 'Aktif')
                    ->where('bank_history.refund_category', 'Refund Customer')
                    ->when($request->periode, function ($query, $periode) {
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
                    ->when($request->start_date, function ($query, $date) {
                        $query->where('invoice.date_publisher', '>=', $date);
                        
                    })->when($request->end_date, function ($query, $date) {
                        $query->where('invoice.date_publisher', '<=', $date);
                    });
                    // ->first();

        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $retur_sale->where('invoice.date_publisher', Carbon::now());
        };

        $retur_sale = $retur_sale->first();

        $retur_purchase = Invoice::select(DB::raw('SUM(bank_history.nominal) as price'))
            ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
            ->where('invoice.status', 'Aktif')
            ->where('bank_history.refund_category', 'Refund Supplier')
            ->when($request->periode, function ($query, $periode) {
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
            ->when($request->start_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '>=', $date);
                
            })->when($request->end_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '<=', $date);
            });
            // ->first();

        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $retur_purchase->where('invoice.date_publisher', Carbon::now());
        };

        $retur_purchase = $retur_purchase->first();

        $ppn = Invoice::select(DB::raw('SUM(bank_history.nominal) as ppn'))
            ->join('bank_history', 'invoice.id', 'bank_history.invoice_id')
            ->where('bank_history.type', 'tax')
            ->when($request->periode, function ($query, $periode) {
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
            ->when($request->start_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '>=', $date);
                
            })->when($request->end_date, function ($query, $date) {
                $query->where('invoice.date_publisher', '<=', $date);
            });
            // ->first();
     
        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $ppn->where('invoice.date_publisher', Carbon::now());
        };

        $ppn = $ppn->first();

        
        $expense = Expense::when($request->start_date, function ($query, $date) {
            $query->where('date', '>=', $date);
            
        })
        ->when($request->periode, function ($query, $periode) {
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
        ->when($request->end_date, function ($query, $date) {
            $query->where('date', '<=', $date);
        });

        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $expense->where('date', Carbon::now());
        };

        $expense = $expense->get();

        return view('backend.profit_loss.index', compact('title', 'invoice', 'ppn', 'retur_sale', 'retur_purchase', 'expense', 'start_date', 'end_date', 'periode'));
    }

    public function export(Request $request)
    {
        $periode = $request->periode;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return Excel::download(new ProfitLossExport($periode, $start_date, $end_date), 'laba_rugi.xlsx');
    }
}
