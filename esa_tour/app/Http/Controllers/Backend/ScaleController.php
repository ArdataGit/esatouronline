<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Bank;
use App\Models\Deposit;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScaleController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Neraca';
    }

    public function index(Request $request)
    {
        $title      = $this->title;
        $periode = $request->periode;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Bank
        $bank   = Bank::select('bank.bank_name', DB::raw('SUM(bank_history.nominal) as income'))
                ->join('bank_history', 'bank.id', 'bank_history.bank_id')
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'last_1_month') {
                        $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                        $today = Carbon::now()->endOfDay();
                        
                        $query->whereBetween('bank_history.date', [$oneMonthAgo, $today]);
                    }else{
                        // dd('masok');
                        $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                        $today = Carbon::now()->endOfDay();

                        $query->whereBetween('bank_history.date', [$oneYearAgo, $today]);
                    }
                })->when($request->start_date, function ($query, $date) {
                    $query->where('bank_history.date', '>=', $date);
                    
                })->when($request->end_date, function ($query, $date) {
                    $query->where('bank_history.date', '<=', $date);
                });


        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $bank->where('bank_history.date', Carbon::now());
        };

        $bank = $bank->groupBy('bank.bank_name')->get();
        

        // Piutang
        $piutang = Invoice::where('status', 'Aktif')
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'last_1_month') {
                        $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                        $today = Carbon::now()->endOfDay();
                        
                        $query->whereBetween('date_publisher', [$oneMonthAgo, $today]);
                    }else{
                        // dd('masok');
                        $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                        $today = Carbon::now()->endOfDay();

                        $query->whereBetween('date_publisher', [$oneYearAgo, $today]);
                    }
                })->when($request->start_date, function ($query, $date) {
                    $query->where('date_publisher', '>=', $date);
                    
                })->when($request->end_date, function ($query, $date) {
                    $query->where('date_publisher', '<=', $date);
                });

        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $piutang->where('date_publisher', Carbon::now());
        };

        $piutang = $piutang->get()->sum('receivables');

        $deposit = Deposit::select('deposit.name', DB::raw('SUM(deposit_history.nominal) as income'))
                ->join('deposit_history', 'deposit.id', 'deposit_history.deposit_id')
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'last_1_month') {
                        $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                        $today = Carbon::now()->endOfDay();
                        
                        $query->whereBetween('deposit_history.date', [$oneMonthAgo, $today]);
                    }else{
                        // dd('masok');
                        $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                        $today = Carbon::now()->endOfDay();

                        $query->whereBetween('deposit_history.date', [$oneYearAgo, $today]);
                    }
                })->when($request->start_date, function ($query, $date) {
                    $query->where('deposit_history.date', '>=', $date);
                    
                })->when($request->end_date, function ($query, $date) {
                    $query->where('deposit_history.date', '<=', $date);
                });


        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $deposit->where('deposit_history.date', Carbon::now());
        };

        $deposit = $deposit->groupBy('deposit.name')->get();;
        // dd($deposit);


        $hutang = Invoice::select(DB::raw('SUM(invoice_d.debt_to_vendors) as debt_to_vendors'), 'products.product_category')
                ->join('invoice_d', 'invoice.id', 'invoice_d.invoice_id')
                ->join('products', 'products.id', 'invoice_d.category_id')
                ->where('invoice_d.debt_to_vendors', '!=', null)
                // ->groupBy('products.product_category')
                // ->get();
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
            $hutang->where('invoice.date_publisher', Carbon::now());
        };

        $hutang = $hutang->groupBy('products.product_category')->get();

        $asset = Asset::when($request->periode, function ($query, $periode) {
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
            })->when($request->start_date, function ($query, $date) {
                $query->where('date', '>=', $date);
                
            })->when($request->end_date, function ($query, $date) {
                $query->where('date', '<=', $date);
            });

        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $asset->where('date', Carbon::now());
        };

        $asset = $asset->get();


        $profit = Invoice::where('status', 'Aktif')
                ->when($request->periode, function ($query, $periode) {
                    if ($periode == 'last_1_month') {
                        $oneMonthAgo = Carbon::now()->subMonth()->startOfDay();
                        $today = Carbon::now()->endOfDay();
                        
                        $query->whereBetween('date_publisher', [$oneMonthAgo, $today]);
                    }else{
                        // dd('masok');
                        $oneYearAgo = Carbon::now()->subYear()->startOfDay();
                        $today = Carbon::now()->endOfDay();

                        $query->whereBetween('date_publisher', [$oneYearAgo, $today]);
                    }
                })->when($request->start_date, function ($query, $date) {
                    $query->where('date_publisher', '>=', $date);
                    
                })->when($request->end_date, function ($query, $date) {
                    $query->where('date_publisher', '<=', $date);
                });

        if ($request->start_date == null && $request->periode == null && $request->end_date == null) {
            $profit->where('date_publisher', Carbon::now());
        };

        $profit = $profit->get()->sum('total_profit');

        return view('backend.scale.index', compact('title', 'bank', 'piutang', 'deposit', 'hutang', 'asset', 'profit', 'periode', 'start_date', 'end_date'));
    }

}
