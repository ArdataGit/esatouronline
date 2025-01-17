<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\DepositHistory;
use Carbon\Carbon;


class BankController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Bank';
    }

    public function index()
    {
        $title      = $this->title;
        $banks = Bank::all();
        $deposit = Deposit::all();
                
        return view('backend.bank.index', compact('title', 'banks', 'deposit'));
    }

    public function data()
    {
        $data = Bank::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $accounts   = Customer::all();

        if ($id) {
            $data = Bank::find($id);
        }
                
        return view('backend.bank.form', compact('title', 'action', 'data', 'accounts'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $nominal = $request->beginning_balance;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);

        try {
            if ($request->bank_id) {
                $data = Bank::find($request->bank_id);

                $requestData = array_merge($request->all(), [
                    'updated_user'      => Auth::user()->id,
                    'balance'           => ($data->income + $nominal) - $data->expense,
                    'beginning_balance' => $nominal
                ]);
                // calculate_bank_income($data, $nominal);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id,
                    'balance'           => $nominal,
                    'beginning_balance' => $nominal
                ]);

                $data = Bank::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.bank.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Bank::find($id);
            
            $check = BankHistory::where('bank_id', $data->id)->get();
            // dd($check);

            foreach ($check as $key => $value) {
                $value->delete();
            }

            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Data Berhasil Dihapus.',
            ]);
        } catch (\Throwable $th) {
            // dd($th->getMessage());

            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal Menyimpan Data, Coba Lagi Kembali',
            ]);
        }
    }

    public function history($id)
    {
        $title      = 'Riwayat Bank';
                
        return view('backend.bank.history', compact('title', 'id'));
    }

    public function history_data(Request $request)
    {
        // dd($request);
        $data = BankHistory::where('bank_id', $request->bank_id)
                ->where(function ($query) {
                    $query->where('status_cashback', '!=', 'Belum Cair')
                        ->orWhere('status_cashback', null);
                });

        // dd($data->get());
        if ($request->date) {
            $data->whereDate('date', $request->date);
        }
     
        return response()->json(['data' => $data->orderBy('created_at', 'desc')->get()]);
    }

    public function transfer(Request $request)
    {
        DB::beginTransaction();

        $nominal = $request->nominal;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);


        try {
            [$source_from, $id] = explode('-', $request->from_bank);

            if ($source_from == 'bank') {
                $from_bank = Bank::find($id);
            }else{
                $from_bank = Deposit::find($id);
            }
       
            [$source_to, $id] = explode('-', $request->to_bank);

            if ($source_to == 'bank') {
                $to_bank = Bank::find($id);
            }else{
                $to_bank = Deposit::find($id);
            }

           
            
            if ($nominal > $from_bank->balance) {
                DB::rollBack();

                return response()->json([
                    'status'    => 400,
                    'message'   => 'Gagal Melakukan Transfer, Saldo Tidak Cukup',
                ]);
            }



            if ($source_from == 'bank') {
                $transaction_name = '';

                if ($source_to == 'bank') {
                   $transaction_name = 'Transfer Bank dari '. $from_bank->bank_name. 'ke Bank '.$to_bank->bank_name.' No Rek: '.$to_bank->account_number;
                }else{
                    $transaction_name = 'Transfer Bank dari '.$from_bank->bank_name.'ke Deposit '.$to_bank->name;
                }

                calculate_bank_expense($from_bank, $nominal, true);

                $create = BankHistory::create([
                    'bank_id'           => $from_bank->id,
                    'transaction_name'  => $transaction_name,
                    'date'              => Carbon::now(),
                    'type'              => 'transfer_expense',
                    'nominal'           => $nominal,
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ]);
            }else{
                $transaction_name = '';

                if ($source_to == 'bank') {
                   $transaction_name = 'Transfer Deposit dari '. $from_bank->name. 'ke Bank '.$to_bank->bank_name.' No Rek: '.$to_bank->account_number;
                }else{
                    $transaction_name = 'Transfer Deposit dari '.$from_bank->name.'ke Deposit '.$to_bank->name;
                }

                calculate_deposit_expense($from_bank, $nominal, true);


                $create = DepositHistory::create([
                    'deposit_id'        => $from_bank->id,
                    'transaction_name'  => $transaction_name,
                    'date'              => Carbon::now(),
                    'type'              => 'transfer_expense',
                    'nominal'           => $nominal,
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ]);
            }
            
            if ($source_to == 'bank') {
                $transaction_name = '';

                if ($source_from == 'bank') {
                   $transaction_name = 'Saldo Masuk dari Bank '. $from_bank->bank_name. 'ke Bank '.$to_bank->bank_name.' No Rek: '.$to_bank->account_number;
                }else{
                    $transaction_name = 'Saldo Masuk dari Deposit '.$from_bank->name.'ke Bank '.$to_bank->bank_name;
                }

                
                calculate_bank_income($to_bank, $nominal);
    
                $create = BankHistory::create([
                    'bank_id'           => $to_bank->id,
                    'transaction_name'  => $transaction_name,
                    'date'              => Carbon::now(),
                    'type'              => 'transfer_income',
                    'nominal'           => $nominal,
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ]);
            }else{
                $transaction_name = '';

                if ($source_from == 'bank') {
                   $transaction_name = 'Saldo Masuk dari Bank '. $from_bank->bank_name. ' ke Deposit '.$to_bank->name;
                }else{
                    $transaction_name = 'Saldo Masuk dari Deposit '.$from_bank->name.' ke Deposit '.$to_bank->name;
                }

                calculate_deposit_income($to_bank, $nominal);

                $create = DepositHistory::create([
                    'deposit_id'        => $to_bank->id,
                    'transaction_name'  => $transaction_name,
                    'date'              => Carbon::now(),
                    'type'              => 'transfer_income',
                    'nominal'           => $nominal,
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ]);
            }
          

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Berhasil Menyimpan Data',
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
