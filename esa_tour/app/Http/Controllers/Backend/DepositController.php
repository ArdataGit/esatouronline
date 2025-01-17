<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\Deposit;
use App\Models\DepositHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DepositController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Deposit';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.deposit.index', compact('title'));
    }

    public function data()
    {
        $data = Deposit::with('bank')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        // $banks   = Bank::all();

        if ($id) {
            $data = Deposit::find($id);
        }
                
        return view('backend.deposit.form', compact('title', 'action', 'data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $nominal = $request->beginning_balance;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);
    
        // Convert the nominal to a float if needed
        $nominal = floatval($nominal);
        // dd($nominal);

        try {
            // $check = Deposit::find($request->bank_id);

            // // dd($nominal);
            // if ($nominal > $bank->balance) {
            //     DB::rollBack();
            //     Alert::error('Error', 'Tidak Bisa Melakukan Deposit, Saldo Bank Rp. '.number_format($bank->balance, 2));
            //     return redirect()->back();
            // }


            if ($request->deposit_id) {
                // $check = BankHistory::where('deposit_id', $request->deposit_id)
                //         ->where('type', 'deposit')
                //         ->get();

                // foreach ($check as $key => $value) {
                //     $data = Deposit::find($value->deposit_id);
                //     $value->delete();
                // }

                $data = Deposit::find($request->deposit_id);
                $requestData = array_merge($request->all(), [
                    'updated_user'      => Auth::user()->id,
                    'balance'           => ($data->income + $nominal) - $data->expense,
                    'beginning_balance' => $nominal
                ]);

                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'date'          => Carbon::now(),
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'balance'           => $nominal,
                    'beginning_balance' => $nominal
                ]);

                $data = Deposit::create($requestData);
            }

            $transaction_name = 'Deposit dari '.$request->name;

            $create = DepositHistory::create([
                'transaction_name'  => $transaction_name,
                'deposit_id'        => $data->id,
                'date'              => Carbon::now(),
                'type'              => 'deposit',
                'nominal'           => $nominal,
                'created_user'      => Auth::user()->id,
                'updated_user'      => Auth::user()->id
            ]);

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.deposit.index');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi Kesalahan Pada Server, Coba Lagi Kembali');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = Deposit::find($id);

            $check = BankHistory::where('deposit_id', $data->id)
            ->where('type', 'deposit')
            ->get();

            foreach ($check as $key => $value) {
                $bank = Bank::find($data->bank_id);
                calculate_bank_expense($bank, $value->nominal);
                
                $value->delete();
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

    
    public function history($id)
    {
        $title      = 'Riwayat Deposit';
                
        return view('backend.deposit.history', compact('title', 'id'));
    }

    
    public function history_data(Request $request)
    {
        // dd($request);
        $data = DepositHistory::where('deposit_id', $request->deposit_id);

        // dd($data->get());
        if ($request->date) {
            $data->whereDate('date', $request->date);
        }
     
        return response()->json(['data' => $data->orderBy('created_at', 'desc')->get()]);
    }
}
