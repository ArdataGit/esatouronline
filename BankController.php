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
                
        return view('backend.bank.index', compact('title', 'banks'));
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
     
        return response()->json(['data' => $data->get()]);
    }

    public function transfer(Request $request)
    {
        DB::beginTransaction();

        $nominal = $request->nominal;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);


        try {
            $from_bank = Bank::find($request->from_bank);
            $to_bank = Bank::find($request->to_bank);
            
            if ($nominal > $from_bank->balance) {
                DB::rollBack();

                return response()->json([
                    'status'    => 400,
                    'message'   => 'Gagal Melakukan Transfer, Saldo Tidak Cukup',
                ]);
            }

            calculate_bank_expense($from_bank, $nominal, true);

            $create = BankHistory::create([
                'bank_id'           => $from_bank->id,
                'transaction_name'  => 'Transfer Bank ke '.$to_bank->account_name.' Bank '.$to_bank->bank_name.' No Rek: '.$to_bank->account_number,
                'date'              => Carbon::now(),
                'type'              => 'transfer_expense',
                'nominal'           => $nominal,
                'created_user'      => Auth::user()->id,
                'updated_user'      => Auth::user()->id
            ]);

            calculate_bank_income($to_bank, $nominal);

            $create = BankHistory::create([
                'bank_id'           => $to_bank->id,
                'transaction_name'  => 'Transfer Bank dari '.$from_bank->account_name.' Bank '.$from_bank->bank_name.' No Rek: '.$from_bank->account_number,
                'date'              => Carbon::now(),
                'type'              => 'transfer_income',
                'nominal'           => $nominal,
                'created_user'      => Auth::user()->id,
                'updated_user'      => Auth::user()->id
            ]);

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
