<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\CategoryNote;
use App\Models\CreditDebit;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class CreditDebitController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Credit & Debit Note';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.credit_debit.index', compact('title'));
    }

    public function data()
    {
        // dd('masok');
        $data = CreditDebit::with('bank')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $banks   = Bank::all();
        $invoices = Invoice::all();
        $categories_note = CategoryNote::all();

        if ($id) {
            $action = 'Edit';
            $data = CreditDebit::find($id);
        }
                
        return view('backend.credit_debit.form', compact('title', 'action', 'data', 'banks', 'invoices', 'categories_note'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $nominal = $request->nominal;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);

        try {
            $bank = Bank::find($request->bank_id);

            if ($request->credit_debit_id) {
                if ($request->type == 'Kredit') {
                    $bank_h = BankHistory::where([
                        'credit_debit_id'   => $request->credit_debit_id
                    ])->latest()->first();
                    
                    calculate_bank_income($bank, $bank_h->nominal, true);
                }else{
                    $bank_h = BankHistory::where([
                        'credit_debit_id'   => $request->credit_debit_id
                    ])->latest()->first();
                    
                    calculate_bank_expense($bank, $bank_h->nominal);
                }

                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                    'nominal'   => $nominal
                ]);

                $data = CreditDebit::find($request->credit_debit_id);
                $data->update($requestData);

                if ($request->type == 'Kredit') {
                    $transaction_name = 'Edit Kredit Note : '.$request->name.' Dilakukan Sebesar Rp. '.$nominal;
                    
                    calculate_bank_income($bank, $nominal);
                }else{
                    $transaction_name = 'Debit Note : '.$request->name. ' Dilakukan Sebesar Rp. '.$nominal;
                    calculate_bank_expense($bank, $nominal, true);
                }
                
                // dd($bank_h);
                $bank_h->update([
                    'transaction_name'  => $transaction_name,
                    'nominal'           => $nominal
                ]);
            }else{

                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'nominal'   => $nominal
                ]);

                $data = CreditDebit::create($requestData);

                if ($request->type == 'Kredit') {
                    $transaction_name = 'Kredit Note : '.$request->name.' Dilakukan Sebesar Rp. '.$nominal;

                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id,
                        'transaction_name'  => $transaction_name,
                        'credit_debit_id'   => $data->id,
                        'date'              => $request->date,
                        'type'              => 'credit',
                        'nominal'           => $nominal,
                        'note'              => $request->note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);

                    calculate_bank_income($bank, $nominal);
                  
                }else{
                    $transaction_name = 'Debit Note : '.$request->name.' Dilakukan Sebesar Rp. '.$nominal;
                 
                    $create = BankHistory::create([
                        'bank_id'           => $request->bank_id,
                        'transaction_name'  => $transaction_name,
                        'credit_debit_id'   => $data->id,
                        'date'              => $request->date,
                        'type'              => 'debit',
                        'nominal'           => $nominal,
                        'note'              => $request->note,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);

                    calculate_bank_expense($bank, $nominal, true);
                }
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.credit_debit.index');
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
            $data = CreditDebit::find($id);
            $bank = Bank::find($data->bank_id);

            if ($data->type == 'Kredit') {
                calculate_bank_income($bank, $data->nominal, true);
            }else{
                calculate_bank_expense($bank, $data->nominal);
            }


            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }
}
