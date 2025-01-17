<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PhysicalInvoice;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhysicalInvoiceController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Invoice Fisik';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.physical_invoice.index', compact('title'));
    }

    public function data()
    {
        $data = PhysicalInvoice::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';

        if ($id) {
            $data = PhysicalInvoice::find($id);
        }
                
        return view('backend.physical_invoice.form', compact('title', 'action', 'data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->physical_invoice_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = PhysicalInvoice::find($request->bank_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = PhysicalInvoice::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.physical_invoice.index');
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
            $data = PhysicalInvoice::find($id);
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
