<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\CategoryExpense;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Pengeluaran';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.expense.index', compact('title'));
    }

    public function data()
    {
        // dd('masok');
        $data = Expense::with('bank', 'category_expense')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $banks   = Bank::all();
        $category_expense = CategoryExpense::all();

        if ($id) {
            $action = 'Edit';
            $data = Expense::find($id);
        }
                
        return view('backend.expense.form', compact('title', 'action', 'data', 'banks', 'category_expense'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $nominal = format_nominal($request->nominal);
            // dd($nominal);
            $bank = Bank::find($request->bank_id);

            if ($nominal > $bank->balance) {
                DB::rollBack();
                Alert::error('Error', 'Gagal Menyimpan Data, Saldo Bank Rp. '.number_format($bank->balance, 2));
                return redirect()->back();
            }


            if ($request->expense_id) {
                $bank_h = BankHistory::where('expense_id', $request->expense_id)->first();

                if ($bank_h) {
                    calculate_bank_expense($bank, $bank_h->nominal);
                }

             
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                    'nominal'       => $nominal
                ]);

                $data = Expense::find($request->expense_id);
                // dd($data);
               

                $data->update($requestData);

                calculate_bank_expense($bank, $nominal, true);

                if ($bank_h) {
                    $trans_name = 'Edit Pengeluaran '. $request->name .'Nominal Awal Rp. '. number_format($bank_h->nominal, 2) .' Menjadi Rp. '.number_format($nominal, 2). ' Ket: '.$request->note;

                    $bank_h->update([
                        'bank_id'           => $request->bank_id,
                        'transaction_name'  => $trans_name,
                        'date'              => $request->date,
                        'type'              => 'expense',
                        'nominal'           => $nominal,
                        'note'              => $request->note,
                        'updated_user'      => Auth::user()->id
                    ]);
                }else{
                    $trans_name = 'Pengeluaran '. $request->name .' Sebesar Rp. '.number_format($nominal, 2). ' Ket: '.$request->note;

                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id,
                        'transaction_name'  => $trans_name,
                        'expense_id'        => $data->id,
                        'date'              => $request->date,
                        'type'              => 'expense',
                        'nominal'           => $nominal,
                        'note'              => $request->note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);
                }
            
            }else{
                calculate_bank_expense($bank, $nominal, true);

                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'nominal'       => $nominal
                ]);

                $data = Expense::create($requestData);

                $trans_name = 'Pengeluaran '. $request->name .' Sebesar Rp. '.number_format($nominal, 2). ' Ket: '.$request->note;

                $create = BankHistory::create([
                    'bank_id'           => $request->bank_id,
                    'transaction_name'  => $trans_name,
                    'expense_id'        => $data->id,
                    'date'              => $request->date,
                    'type'              => 'expense',
                    'nominal'           => $nominal,
                    'note'              => $request->note,
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ]);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.expense.index');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Expense::find($id);
            $bank = Bank::find($data->bank_id);

            calculate_bank_expense($bank, $data->nominal);

            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus.',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }

    public function category_store(Request $request)
    {
        // dd($request);
        DB::beginTransaction();

        try {
            $requestData = array_merge($request->all(), [
                'name'          => $request->category_name,
                'created_user'  => Auth::user()->id,
                'updated_user'  => Auth::user()->id,
            ]);

            $data = CategoryExpense::create($requestData);

            DB::commit();

            $data = CategoryExpense::all();

            return response()->json([
                'status'    => 200,
                'message'   => 'Berhasil Menyimpan Data',
                'data'      => $data,
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }
}
