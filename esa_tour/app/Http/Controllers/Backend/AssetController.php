<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Bank;
use App\Models\BankHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Modal';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.asset.index', compact('title'));
    }

    public function data()
    {
        // dd('masok');
        $data = Asset::with('bank')->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';
        $banks   = Bank::all();

        if ($id) {
            $action = 'Edit';
            $data = Asset::find($id);
        }
                
        return view('backend.asset.form', compact('title', 'action', 'data', 'banks'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $nominal = $request->nominal;
        $nominal = str_replace('.', '', $nominal);
        $nominal = str_replace(',', '', $nominal);
        $nominal = preg_replace('/[^0-9]/', '', $nominal);
        
        // Convert the nominal to a float if needed
        $nominal = floatval($nominal);
        // dd($nominal);

        try {
            $bank = Bank::find($request->bank_id);

            if ($request->asset_id) {
                $check = BankHistory::where('asset_id', $request->asset_id)
                ->where('type', 'modal')
                ->get();

                foreach ($check as $key => $value) {
                    $bank = Bank::find($value->bank_id);
                    calculate_bank_income($bank, $value->nominal, true);
                    
                    $value->delete();
                }
        
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                    'nominal'   => $nominal
                ]);

                $data = Asset::find($request->asset_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                    'nominal'   => $nominal
                ]);

                $data = Asset::create($requestData);
            }

            calculate_bank_income($bank, $nominal);

            $transaction_name = 'Modal dari '.$request->name;

            $create = BankHistory::create([
                'bank_id'           => $request->bank_id,
                'transaction_name'  => $transaction_name,
                'asset_id'        => $data->id,
                'date'              => $request->date,
                'type'              => 'modal',
                'nominal'           => $nominal,
                'created_user'      => Auth::user()->id,
                'updated_user'      => Auth::user()->id
            ]);


            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.asset.index');
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
            $data = Asset::find($id);
            $bank = Bank::find($data->bank_id);

            calculate_bank_income($bank, $data->nominal, true);

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
