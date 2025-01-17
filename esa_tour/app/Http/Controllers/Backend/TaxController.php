<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\Invoice;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TaxController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Pajak';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.tax.index', compact('title'));
    }

    public function data()
    {
        $data = Tax::with('invoice')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $invoice    = Invoice::all();
        $banks      = Bank::all();

        if ($id) {
            $data = Tax::find($id);
        }
                
        return view('backend.tax.form', compact('title', 'action', 'data', 'invoice', 'banks'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $nominal    = format_nominal($request->nominal);
            $bank       = Bank::find($request->bank_id);
            $invoice    = Invoice::find($request->invoice_id);
            // dd($bank);

            if ($request->tax_id) {
                $bank_h = BankHistory::where('tax_id', $request->tax_id)->first();

                if ($bank_h) {
                    calculate_bank_expense($bank, $bank_h->nominal);
                }
                
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                    'nominal'       => $nominal
                ]);

                $data = Tax::find($request->tax_id);
                $data->update($requestData);

                calculate_bank_expense($bank, $nominal, true);

                if ($bank_h) {
                    $trans_name = 'Edit '.$request->name.' dari '. $invoice->invoice_number .'Nominal Awal Rp. '. number_format($bank_h->nominal, 2) .' Menjadi Rp. '.number_format($nominal, 2);

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
                    $transaction_name = $request->name.' dari '.$invoice->invoice_number. 'Sebesar Rp. '.$nominal;
                    
                    $create = BankHistory::create([
                        'bank_id'           => $bank->id,
                        'transaction_name'  => $transaction_name,
                        'invoice_id'        => $invoice->id,
                        'date'              => $request->date,
                        'type'              => 'tax',
                        'nominal'           => $nominal,
                        'created_user'      => Auth::user()->id,
                        'updated_user'      => Auth::user()->id
                    ]);
                }
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'nominal'       => $nominal
                ]);

                $data = Tax::create($requestData);

                calculate_bank_expense($bank, $nominal, true);

                $transaction_name = $request->name.' dari '.$invoice->invoice_number. 'Sebesar Rp. '.$nominal;
                $create = BankHistory::create([
                    'bank_id'           => $bank->id,
                    'transaction_name'  => $transaction_name,
                    'invoice_id'        => $invoice->id,
                    'date'              => $request->date,
                    'type'              => 'tax',
                    'nominal'           => $nominal,
                    'created_user'      => Auth::user()->id,
                    'updated_user'      => Auth::user()->id
                ]);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.tax.index');
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
            $data = Tax::find($id);
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
