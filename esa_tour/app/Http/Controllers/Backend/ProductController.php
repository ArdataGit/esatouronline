<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public $title;

    public function __construct()
    {
        $this->title = 'Produk';
    }

    public function index()
    {
        $title      = $this->title;
                
        return view('backend.product.index', compact('title'));
    }

    public function data()
    {
        $data = Product::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $data]);
    }

    public function form($id = null)
    {
        $data       = null;
        $title      = $this->title;
        $action     = 'Tambah';

        if ($id) {
            $data = Product::find($id);
        }
                
        return view('backend.product.form', compact('title', 'action', 'data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->product_id) {
                $requestData = array_merge($request->all(), [
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Product::find($request->product_id);
                $data->update($requestData);
            }else{
                $requestData = array_merge($request->all(), [
                    'created_user'  => Auth::user()->id,
                    'updated_user'  => Auth::user()->id,
                ]);

                $data = Product::create($requestData);
            }

            DB::commit();


            Alert::success('Sukses', 'Berhasil Menyimpan Data');
            return redirect()->route('backend.product.index');
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
            $data = Product::find($id);
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
