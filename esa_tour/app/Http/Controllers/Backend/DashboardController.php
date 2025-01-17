<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BankHistory;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Home';
    }

    public function index()
    {
        $title = $this->title;
        $currentMonth = Carbon::now()->month;
        $invoice = Invoice::where('status', 'Aktif')->orderBy('created_at', 'desc')->take(5)->get();
        $today_income = BankHistory::whereDate('date', Carbon::now())
                ->whereIn('type', ['customer_payment', 'cashback'])    
                ->sum('nominal');

        $month_income = BankHistory::whereMonth('date', $currentMonth)
                ->whereIn('type', ['customer_payment', 'cashback'])    
                ->sum('nominal');

        $today_expense = BankHistory::whereDate('date', Carbon::now())
                ->whereIn('type', ['vendor_payment', 'refund', 'tax'])    
                ->sum('nominal');

        $month_expense = BankHistory::whereMonth('date', $currentMonth)
                ->whereIn('type', ['vendor_payment', 'refund', 'tax'])    
                ->sum('nominal');
                
        return view('backend.dashboard.index', compact('title', 'invoice', 'today_income', 'month_income', 'today_expense', 'month_expense'));
    }

    public function total_invoice(Request $request)
    {
        if ($request->filter == 'monthly') {
            $currentMonth = Carbon::now()->month;

            $total_invoice = Invoice::whereMonth('date_publisher', $currentMonth)->sum('price_total_selling');
            $total_pay  = BankHistory::whereMonth('date', $currentMonth)->where('type', 'customer_payment')->sum('nominal');
        }else{
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $total_invoice = Invoice::whereBetween('date_publisher', [$startOfWeek, $endOfWeek])->sum('price_total_selling');
            $total_pay  = BankHistory::whereMonth('date', [$startOfWeek, $endOfWeek])->where('type', 'customer_payment')->sum('nominal');            
        }

        return response()->json([
            'total_invoice' => $total_invoice,
            'total_pay' => $total_pay
        ]);
    }
}
